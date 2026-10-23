#!/usr/bin/env bash
# ==============================================================================
# Script: serve-https.sh
# Deskripsi: Menjalankan aplikasi ProCBT dengan protokol HTTPS pada port custom
#            (default 8000) untuk akses jaringan LAN / IP lokal.
# ==============================================================================

set -e

# Pindah ke direktori root aplikasi
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" >/dev/null 2>&1 && pwd)"
cd "$DIR"

# Warna terminal
GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BOLD='\033[1m'
NC='\033[0m'

# Opsi / Argumen parsing
PORT="8000"
BIND_HOST="0.0.0.0"
RENEW_CERT=false

for arg in "$@"; do
    case "$arg" in
        -h|--help)
            echo -e "${CYAN}${BOLD}ProCBT HTTPS Server Runner${NC}"
            echo "Penggunaan: ./serve-https.sh [PORT] [IP_BIND] [OPSI]"
            echo ""
            echo "Argumen:"
            echo "  PORT          : Port HTTPS yang ingin digunakan (Default: 8000)"
            echo "  IP_BIND       : IP binding jaringan (Default: 0.0.0.0 - semua interface)"
            echo ""
            echo "Opsi Reset / Hapus SSL:"
            echo "  --reset-ssl   : Hapus & buat ulang sertifikat SSL (misal jika IP LAN berubah)"
            echo "  --delete-ssl  : Hapus seluruh sertifikat SSL dan keluar (kembali ke HTTP biasa)"
            echo ""
            echo "Contoh:"
            echo "  ./serve-https.sh              # Berjalan pada https://<IP>:8000"
            echo "  ./serve-https.sh 8000         # Tetap pada port 8000"
            echo "  ./serve-https.sh 8443         # Berjalan pada port 8443"
            echo "  ./serve-https.sh --reset-ssl  # Reset & regenerasi sertifikat SSL"
            echo "  ./serve-https.sh --delete-ssl # Hapus file SSL"
            echo ""
            exit 0
            ;;
        --delete-ssl|--clean-ssl|--remove-ssl)
            echo -e "${YELLOW}>> Menghapus file sertifikat SSL di $DIR/storage/ssl...${NC}"
            rm -rf "$DIR/storage/ssl"
            echo -e "${GREEN}✓ File SSL berhasil dihapus.${NC}"
            echo ""
            echo -e "Untuk menjalankan aplikasi kembali menggunakan ${BOLD}HTTP biasa (non-SSL)${NC}:"
            echo -e "  ${GREEN}${BOLD}php artisan serve --port=8000 --host=0.0.0.0${NC}"
            echo ""
            exit 0
            ;;
        --reset-ssl|--new-cert|--renew-cert)
            RENEW_CERT=true
            ;;
        [0-9]*)
            PORT="$arg"
            ;;
        *.*.*.*)
            BIND_HOST="$arg"
            ;;
    esac
done

SSL_DIR="$DIR/storage/ssl"
CERT_FILE="$SSL_DIR/cert.pem"
KEY_FILE="$SSL_DIR/key.pem"
CADDY_CONFIG="$SSL_DIR/Caddyfile"

mkdir -p "$SSL_DIR"

if [ "$RENEW_CERT" = true ]; then
    rm -f "$CERT_FILE" "$KEY_FILE"
fi

# 1. Deteksi IP Jaringan Lokal Mesin Ini
IP_LIST=()
if command -v hostname >/dev/null 2>&1; then
    for ip in $(hostname -I 2>/dev/null); do
        if [[ "$ip" =~ ^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$ ]] && [ "$ip" != "127.0.0.1" ]; then
            IP_LIST+=("$ip")
        fi
    done
fi

if [ ${#IP_LIST[@]} -eq 0 ] && command -v ip >/dev/null 2>&1; then
    for ip in $(ip -4 addr show scope global | grep -oP '(?<=inet\s)\d+(\.\d+){3}'); do
        IP_LIST+=("$ip")
    done
fi

PRIMARY_IP="${IP_LIST[0]:-127.0.0.1}"

# 2. Cek / Generate Sertifikat SSL Self-Signed dengan Subject Alternative Name (SAN)
# Menyertakan localhost, 127.0.0.1, dan seluruh IP LAN mesin agar browser mengizinkan
SAN_ENTRIES="DNS:localhost,IP:127.0.0.1,IP:0.0.0.0"
for ip in "${IP_LIST[@]}"; do
    SAN_ENTRIES+=",IP:$ip"
done

if [ ! -f "$CERT_FILE" ] || [ ! -f "$KEY_FILE" ]; then
    echo -e "${YELLOW}>> Menyiapkan sertifikat SSL lokal (Self-Signed dengan SAN IP)...${NC}"
    openssl req -x509 -newkey rsa:2048 -nodes \
        -keyout "$KEY_FILE" \
        -out "$CERT_FILE" \
        -days 3650 \
        -subj "/C=ID/ST=Indonesia/L=Local/O=ProCBT/OU=Exam/CN=ProCBT-Local" \
        -addext "subjectAltName=$SAN_ENTRIES" 2>/dev/null || {
            # Fallback untuk OpenSSL versi lama yang belum mendukung -addext
            OPENSSL_CONF="$SSL_DIR/openssl.cnf"
            cat > "$OPENSSL_CONF" <<EOF
[req]
distinguished_name = req_distinguished_name
x509_extensions = v3_req
prompt = no

[req_distinguished_name]
C = ID
ST = Indonesia
L = Local
O = ProCBT
CN = ProCBT-Local

[v3_req]
keyUsage = critical, digitalSignature, keyEncipherment
extendedKeyUsage = serverAuth
subjectAltName = $SAN_ENTRIES
EOF
            openssl req -x509 -newkey rsa:2048 -nodes \
                -keyout "$KEY_FILE" \
                -out "$CERT_FILE" \
                -days 3650 \
                -config "$OPENSSL_CONF" 2>/dev/null
            rm -f "$OPENSSL_CONF"
        }
    chmod 600 "$KEY_FILE"
    echo -e "${GREEN}✓ Sertifikat SSL berhasil dibuat di $SSL_DIR${NC}"
fi

# 3. Cek apakah Port Sudah Terpakai
check_port() {
    local p=$1
    if command -v lsof >/dev/null 2>&1; then
        lsof -iTCP:"$p" -sTCP:LISTEN -t 2>/dev/null || true
    elif command -v fuser >/dev/null 2>&1; then
        fuser "$p/tcp" 2>/dev/null || true
    elif command -v ss >/dev/null 2>&1; then
        ss -ltn "sport = :$p" | grep -q "$p" && echo "occupied" || true
    fi
}

PID_ON_PORT=$(check_port "$PORT")
if [ -n "$PID_ON_PORT" ]; then
    echo -e "${YELLOW}! Perhatian: Port $PORT tampaknya sedang digunakan.${NC}"
    echo -e "  Mencoba menghentikan proses lama pada port $PORT..."
    if command -v fuser >/dev/null 2>&1; then
        fuser -k "$PORT/tcp" 2>/dev/null || true
        sleep 1
    elif [[ "$PID_ON_PORT" =~ ^[0-9]+$ ]]; then
        kill -9 "$PID_ON_PORT" 2>/dev/null || true
        sleep 1
    fi
fi

# 4. Deteksi Server Engine (FrankenPHP atau Fallback PHP + SSL Proxy)
FRANKENPHP_BIN=""
if [ -x "$DIR/frankenphp" ]; then
    FRANKENPHP_BIN="$DIR/frankenphp"
elif command -v frankenphp >/dev/null 2>&1; then
    FRANKENPHP_BIN="$(command -v frankenphp)"
fi

cleanup() {
    echo ""
    echo -e "${YELLOW}>> Menghentikan server HTTPS...${NC}"
    if [ -n "$SERVER_PID" ]; then
        kill -SIGINT "$SERVER_PID" 2>/dev/null || true
        wait "$SERVER_PID" 2>/dev/null || true
    fi
    if [ -n "$INTERNAL_PHP_PID" ]; then
        kill "$INTERNAL_PHP_PID" 2>/dev/null || true
    fi
    echo -e "${GREEN}✓ Server dihentikan dengan aman.${NC}"
    exit 0
}
trap cleanup SIGINT SIGTERM EXIT

# Tampilkan Banner Informasi
echo -e "${CYAN}====================================================================${NC}"
echo -e "${CYAN}${BOLD}                    PROCBT LOCAL HTTPS SERVER                       ${NC}"
echo -e "${CYAN}====================================================================${NC}"
echo -e " Port HTTPS   : ${GREEN}${BOLD}$PORT${NC} (Akses langsung https, tetap di port $PORT)"
echo -e " IP Bind      : ${GREEN}$BIND_HOST${NC}"
echo -e " Sertifikat   : ${GREEN}$CERT_FILE${NC}"

# 5. Jalankan Server sesuai Engine
if [ -n "$FRANKENPHP_BIN" ] && "$FRANKENPHP_BIN" version >/dev/null 2>&1; then
    echo -e " Server Engine: ${GREEN}${BOLD}FrankenPHP (High-Performance Engine)${NC}"
    
    # Buat Caddyfile Khusus Port & SSL Ini
    cat > "$CADDY_CONFIG" <<EOF
{
    admin off
    auto_https disable_redirects
    log {
        level ERROR
    }
}

:$PORT {
    tls "$CERT_FILE" "$KEY_FILE"
    root * "$DIR/public"
    encode zstd gzip

    # Set HTTPS header agar Laravel selalu mengenali request sebagai HTTPS
    header {
        X-Forwarded-Proto "https"
        Strict-Transport-Security "max-age=31536000;"
    }

    route {
        php_server {
            index index.php
            try_files {path} index.php
            resolve_root_symlink
        }
    }
}
EOF

    echo -e "${CYAN}--------------------------------------------------------------------${NC}"
    echo -e "${BOLD}Alamat URL yang siap diakses di Browser:${NC}"
    echo -e "  Localhost : ${GREEN}${BOLD}https://localhost:$PORT${NC}"
    echo -e "  Loopback  : ${GREEN}${BOLD}https://127.0.0.1:$PORT${NC}"
    for ip in "${IP_LIST[@]}"; do
        echo -e "  Jaringan  : ${GREEN}${BOLD}https://$ip:$PORT${NC}"
    done
    echo ""
    echo -e "${YELLOW}${BOLD}PANDUAN AKSES DI BROWSER SISWA / PROCTOR:${NC}"
    echo -e " 1. Buka URL: ${GREEN}https://$PRIMARY_IP:$PORT${NC}"
    echo -e " 2. Jika muncul peringatan 'Koneksi Anda Tidak Privat' (karena SSL lokal):"
    echo -e "    - Klik ${BOLD}'Lanjutan'${NC} atau ${BOLD}'Advanced'${NC}"
    echo -e "    - Klik ${BOLD}'Lanjutkan ke $PRIMARY_IP (tidak aman)'${NC}"
    echo -e " 3. Fitur ${BOLD}Live Stream / Kamera${NC} otomatis aktif karena berjalan di HTTPS!"
    echo -e "${CYAN}====================================================================${NC}"
    echo -e "Tekan ${YELLOW}[Ctrl + C]${NC} untuk menghentikan server."
    echo ""

    export HTTPS=on
    export SERVER_PORT="$PORT"
    "$FRANKENPHP_BIN" run --config "$CADDY_CONFIG" &
    SERVER_PID=$!
    wait "$SERVER_PID"

else
    # Fallback Engine: PHP Built-in Server + Python SSL Reverse Proxy
    echo -e " Server Engine: ${YELLOW}${BOLD}PHP Built-in Server + SSL Proxy (Fallback)${NC}"

    INTERNAL_PORT=8001
    while [ "$INTERNAL_PORT" -eq "$PORT" ] || [ -n "$(check_port "$INTERNAL_PORT")" ]; do
        INTERNAL_PORT=$((INTERNAL_PORT + 1))
    done

    # Jalankan PHP server di background pada port internal
    export HTTPS=on
    php -S 127.0.0.1:"$INTERNAL_PORT" -t "$DIR/public" "$DIR/public/index.php" >/dev/null 2>&1 &
    INTERNAL_PHP_PID=$!
    sleep 1

    PROXY_SCRIPT="$SSL_DIR/proxy.py"
    cat > "$PROXY_SCRIPT" <<'PYEOF'
import sys, socket, ssl, threading

listen_port = int(sys.argv[1])
target_port = int(sys.argv[2])
cert_file = sys.argv[3]
key_file = sys.argv[4]

context = ssl.SSLContext(ssl.PROTOCOL_TLS_SERVER)
context.load_cert_chain(certfile=cert_file, keyfile=key_file)

def forward(source, destination):
    try:
        while True:
            data = source.recv(65536)
            if not data:
                break
            destination.sendall(data)
    except Exception:
        pass
    finally:
        try: source.close()
        except: pass
        try: destination.close()
        except: pass

def handle_client(client_ssl):
    try:
        backend = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        backend.connect(('127.0.0.1', target_port))
        t1 = threading.Thread(target=forward, args=(client_ssl, backend), daemon=True)
        t2 = threading.Thread(target=forward, args=(backend, client_ssl), daemon=True)
        t1.start()
        t2.start()
    except Exception:
        try: client_ssl.close()
        except: pass

bind_sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
bind_sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
bind_sock.bind(('0.0.0.0', listen_port))
bind_sock.listen(128)

while True:
    try:
        raw_client, addr = bind_sock.accept()
        client_ssl = context.wrap_socket(raw_client, server_side=True)
        threading.Thread(target=handle_client, args=(client_ssl,), daemon=True).start()
    except Exception:
        continue
PYEOF

    echo -e "${CYAN}--------------------------------------------------------------------${NC}"
    echo -e "${BOLD}Alamat URL yang siap diakses di Browser:${NC}"
    echo -e "  Localhost : ${GREEN}${BOLD}https://localhost:$PORT${NC}"
    echo -e "  Loopback  : ${GREEN}${BOLD}https://127.0.0.1:$PORT${NC}"
    for ip in "${IP_LIST[@]}"; do
        echo -e "  Jaringan  : ${GREEN}${BOLD}https://$ip:$PORT${NC}"
    done
    echo ""
    echo -e "${YELLOW}${BOLD}PANDUAN AKSES DI BROWSER SISWA / PROCTOR:${NC}"
    echo -e " 1. Buka URL: ${GREEN}https://$PRIMARY_IP:$PORT${NC}"
    echo -e " 2. Klik ${BOLD}'Lanjutan'${NC} atau ${BOLD}'Advanced'${NC} -> ${BOLD}'Lanjutkan ke $PRIMARY_IP (tidak aman)'${NC}"
    echo -e " 3. Fitur ${BOLD}Live Stream / Kamera${NC} otomatis aktif karena berjalan di HTTPS!"
    echo -e "${CYAN}====================================================================${NC}"
    echo -e "Tekan ${YELLOW}[Ctrl + C]${NC} untuk menghentikan server."
    echo ""

    python3 "$PROXY_SCRIPT" "$PORT" "$INTERNAL_PORT" "$CERT_FILE" "$KEY_FILE" &
    SERVER_PID=$!
    wait "$SERVER_PID"
fi
