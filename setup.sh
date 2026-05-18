#!/bin/bash

# Warna untuk output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}===============================================${NC}"
echo -e "${YELLOW}       INSTALLATION & LICENSE SETUP CBT        ${NC}"
echo -e "${YELLOW}===============================================${NC}"

# 1. Copy .env
if [ ! -f .env ]; then
    echo -e "${GREEN}[1/5] Menyalin .env.example ke .env...${NC}"
    cp .env.example .env
else
    echo -e "${YELLOW}[1/5] File .env sudah ada. Menggunakan file yang ada.${NC}"
fi

# Fungsi untuk mengupdate file .env
update_env() {
    local key=$1
    local value=$2
    # Cek apakah key sudah ada
    if grep -q "^$key=" .env; then
        # Jika ada, ganti nilainya
        sed -i "s|^$key=.*|$key=$value|" .env
    else
        # Jika tidak ada, tambahkan di akhir
        echo "$key=$value" >> .env
    fi
}

# 2. Input Konfigurasi Penting (Satu per satu)
echo -e "\n${GREEN}[2/5] Konfigurasi .env (Tekan Enter untuk nilai default)${NC}"

read -p "Masukkan APP_NAME [CBT Procbt]: " app_name
app_name=${app_name:-"CBT Procbt"}
update_env "APP_NAME" "\"$app_name\""

read -p "Masukkan APP_URL [https://domainanda.com]: " app_url
app_url=${app_url:-"https://domainanda.com"}
update_env "APP_URL" "$app_url"

read -p "Masukkan DB_HOST [127.0.0.1]: " db_host
db_host=${db_host:-"127.0.0.1"}
update_env "DB_HOST" "$db_host"

read -p "Masukkan DB_PORT [5432]: " db_port
db_port=${db_port:-"5432"}
update_env "DB_PORT" "$db_port"

read -p "Masukkan DB_DATABASE [nama_database]: " db_database
db_database=${db_database:-"nama_database"}
update_env "DB_DATABASE" "$db_database"

read -p "Masukkan DB_USERNAME [username_database]: " db_username
db_username=${db_username:-"username_database"}
update_env "DB_USERNAME" "$db_username"

read -s -p "Masukkan DB_PASSWORD [password_database]: " db_password
echo "" # Baris baru setelah password
db_password=${db_password:-"password_database"}
update_env "DB_PASSWORD" "$db_password"

# Set default settingan khusus yang diminta user
echo -e "${GREEN}Mengatur konfigurasi default CBT...${NC}"
update_env "APP_ENV" "production"
update_env "APP_DEBUG" "false"
update_env "DB_CONNECTION" "pgsql"
update_env "SESSION_DRIVER" "database"
update_env "SESSION_LIFETIME" "120"
update_env "SESSION_ENCRYPT" "false"
update_env "BROADCAST_CONNECTION" "log"
update_env "FILESYSTEM_DISK" "public"
update_env "QUEUE_CONNECTION" "database"
update_env "CACHE_STORE" "database"
update_env "OCTANE_SERVER" "frankenphp"

# Settingan Tambahan dari User
update_env "TALLSTACKUI_PREFIX" "\"ts-\""
update_env "APP_SLUG_NAME" "\"login_universitas\""
update_env "IMPORT_QUESTION" "true"
update_env "QUESTION_DUMMY" "false"
update_env "QUESTION_PACKAGE" "Lite"
update_env "SEB_ENABLED" "true"
update_env "SEB_REQUIRE_ALL" "false"
update_env "SEB_REQUIRE_BEK" "false"
update_env "CLEAR_SESSION_PASSWORD" "procbt"
update_env "LIMIT_QUESTION_VIEW" "false"
update_env "LIMIT_QUESTION_COUNT" "5"

# Generate Fingerprint jika belum ada
if ! grep -q "^LICENSE_FINGERPRINT=" .env || [ -z "$(grep "^LICENSE_FINGERPRINT=" .env | cut -d'=' -f2)" ]; then
    FINGERPRINT=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 32)
    update_env "LICENSE_FINGERPRINT" "$FINGERPRINT"
else
    FINGERPRINT=$(grep "^LICENSE_FINGERPRINT=" .env | cut -d'=' -f2)
fi

# 3. Composer Install
echo -e "\n${GREEN}[3/5] Menjalankan Composer Install...${NC}"
composer install

# Generate APP_KEY jika belum ada
if ! grep -q "^APP_KEY=base64:" .env || [ -z "$(grep "^APP_KEY=" .env | cut -d'=' -f2)" ]; then
    php artisan key:generate
fi

# 4. Input License Key
echo -e "\n${GREEN}[4/5] Verifikasi Lisensi${NC}"
read -p "Masukkan URL Server Lisensi [https://license.procbt.id]: " license_server
license_server=${license_server:-"https://license.procbt.id"}
update_env "LICENSE_SERVER_URL" "$license_server"

read -p "Masukkan LICENSE KEY Anda: " license_key
if [ -z "$license_key" ]; then
    echo -e "${RED}Error: License Key tidak boleh kosong!${NC}"
    exit 1
fi
update_env "LICENSE_KEY" "$license_key"

# 5. Verifikasi ke Server Lisensi
echo -e "${GREEN}[5/5] Menghubungi server lisensi untuk aktivasi...${NC}"

# Mengirim request menggunakan curl ke endpoint activate
RESPONSE=$(curl -s -X POST "$license_server/api/license/activate" \
  -H "Content-Type: application/json" \
  -d "{\"license_key\": \"$license_key\", \"domain\": \"$app_url\", \"fingerprint\": \"$FINGERPRINT\"}")

echo -e "Response dari server: $RESPONSE"

# Cek apakah response mengandung kata '"valid":true'
if echo "$RESPONSE" | grep -q '"valid":true'; then
    echo -e "${GREEN}Sukses: Lisensi berhasil diverifikasi dan diaktifkan!${NC}"
    
    # Jalankan migrasi setelah lisensi valid
    echo -e "${GREEN}Menjalankan database migration...${NC}"
    php artisan migrate --force
    
    echo -e "${YELLOW}Instalasi selesai! Web sekarang dapat diakses.${NC}"
else
    echo -e "${RED}Gagal: License Key tidak valid, sudah digunakan, atau tidak cocok!${NC}"
    # Hapus license key dari .env agar tidak bisa diakses
    update_env "LICENSE_KEY" ""
    exit 1
fi
