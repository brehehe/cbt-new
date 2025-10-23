#!/bin/bash

# =====================================================
# CBT Recording Performance Monitor
# =====================================================
# Purpose: Monitor system performance and resource usage for CBT recording
# Usage: bash monitor-cbt-performance.sh [interval_seconds]

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

# Configuration
INTERVAL=${1:-5}  # Default 5 seconds
APP_DIR="/var/www/html/drshieldapp/procbt.id"
LOG_DIR="/var/log"

echo -e "${BLUE}=====================================================${NC}"
echo -e "${BLUE}    CBT Recording Performance Monitor${NC}"
echo -e "${BLUE}    Monitoring interval: ${INTERVAL}s${NC}"
echo -e "${BLUE}    Press Ctrl+C to stop${NC}"
echo -e "${BLUE}=====================================================${NC}"

# =====================================================
# FUNCTION DEFINITIONS
# =====================================================

get_timestamp() {
    date '+%Y-%m-%d %H:%M:%S'
}

check_system_resources() {
    echo -e "${CYAN}[$(get_timestamp)] System Resources:${NC}"

    # CPU Usage
    CPU_USAGE=$(top -bn1 | grep "Cpu(s)" | awk '{print $2}' | cut -d'%' -f1)
    echo -e "  CPU Usage: ${CPU_USAGE}%"

    # Memory Usage
    MEMORY_INFO=$(free -m | grep '^Mem')
    TOTAL_MEM=$(echo $MEMORY_INFO | awk '{print $2}')
    USED_MEM=$(echo $MEMORY_INFO | awk '{print $3}')
    FREE_MEM=$(echo $MEMORY_INFO | awk '{print $4}')
    MEM_PERCENT=$(( (USED_MEM * 100) / TOTAL_MEM ))

    echo -e "  Memory: ${USED_MEM}MB/${TOTAL_MEM}MB (${MEM_PERCENT}%)"

    # Disk Usage
    DISK_INFO=$(df -h "$APP_DIR" | tail -1)
    DISK_USAGE=$(echo $DISK_INFO | awk '{print $5}')
    DISK_AVAIL=$(echo $DISK_INFO | awk '{print $4}')

    echo -e "  Disk Usage: ${DISK_USAGE} (${DISK_AVAIL} available)"

    # Load Average
    LOAD_AVG=$(uptime | awk -F'load average:' '{print $2}')
    echo -e "  Load Average:${LOAD_AVG}"
}

check_php_processes() {
    echo -e "${CYAN}[$(get_timestamp)] PHP Processes:${NC}"

    # Count PHP-FPM processes
    PHP_PROCESSES=$(ps aux | grep -c 'php-fpm.*pool')
    echo -e "  Active PHP-FPM processes: ${PHP_PROCESSES}"

    # Check PHP-FPM pool status (if available)
    if command -v php-fpm8.3 &> /dev/null; then
        PHP_STATUS=$(systemctl is-active php8.3-fpm)
        echo -e "  PHP-FPM status: ${PHP_STATUS}"
    fi

    # Check for long-running PHP processes
    LONG_PROCESSES=$(ps -eo pid,etime,cmd | grep 'php-fpm' | awk '$2 > "00:10:00" {count++} END {print count+0}')
    if [[ $LONG_PROCESSES -gt 0 ]]; then
        echo -e "  ${YELLOW}Warning: ${LONG_PROCESSES} long-running PHP processes${NC}"
    fi
}

check_nginx_status() {
    echo -e "${CYAN}[$(get_timestamp)] Nginx Status:${NC}"

    # Nginx status
    NGINX_STATUS=$(systemctl is-active nginx)
    echo -e "  Nginx status: ${NGINX_STATUS}"

    # Active connections (if nginx-status module available)
    if command -v ss &> /dev/null; then
        HTTP_CONNECTIONS=$(ss -tuln | grep ':80\|:443' | wc -l)
        echo -e "  HTTP/HTTPS listeners: ${HTTP_CONNECTIONS}"
    fi

    # Check for high connection count
    TOTAL_CONNECTIONS=$(ss -s | grep 'TCP:' | awk '{print $2}')
    echo -e "  Total TCP connections: ${TOTAL_CONNECTIONS}"
}

check_storage_usage() {
    echo -e "${CYAN}[$(get_timestamp)] Storage Analysis:${NC}"

    # App storage usage
    if [[ -d "$APP_DIR/storage" ]]; then
        STORAGE_SIZE=$(du -sh "$APP_DIR/storage" 2>/dev/null | cut -f1)
        echo -e "  App storage size: ${STORAGE_SIZE}"

        # Recordings directory
        if [[ -d "$APP_DIR/storage/app/recordings" ]]; then
            RECORDINGS_SIZE=$(du -sh "$APP_DIR/storage/app/recordings" 2>/dev/null | cut -f1)
            RECORDINGS_COUNT=$(find "$APP_DIR/storage/app/recordings" -name "*.webm" -o -name "*.mp4" 2>/dev/null | wc -l)
            echo -e "  Recordings: ${RECORDINGS_SIZE} (${RECORDINGS_COUNT} files)"
        fi

        # Temporary files
        if [[ -d "$APP_DIR/storage/tmp" ]]; then
            TMP_SIZE=$(du -sh "$APP_DIR/storage/tmp" 2>/dev/null | cut -f1)
            TMP_COUNT=$(find "$APP_DIR/storage/tmp" -type f 2>/dev/null | wc -l)
            echo -e "  Temp files: ${TMP_SIZE} (${TMP_COUNT} files)"

            # Clean old temp files (older than 1 hour)
            OLD_TMP_COUNT=$(find "$APP_DIR/storage/tmp" -type f -mmin +60 2>/dev/null | wc -l)
            if [[ $OLD_TMP_COUNT -gt 0 ]]; then
                echo -e "  ${YELLOW}Found ${OLD_TMP_COUNT} old temp files (>1h)${NC}"
            fi
        fi
    fi

    # System tmp usage
    SYS_TMP_SIZE=$(du -sh /tmp 2>/dev/null | cut -f1)
    echo -e "  System /tmp: ${SYS_TMP_SIZE}"
}

check_log_files() {
    echo -e "${CYAN}[$(get_timestamp)] Log Analysis:${NC}"

    # PHP error logs
    if [[ -f "/var/log/php/cbt-recording-errors.log" ]]; then
        PHP_ERRORS=$(tail -n 100 "/var/log/php/cbt-recording-errors.log" 2>/dev/null | wc -l)
        RECENT_ERRORS=$(tail -n 10 "/var/log/php/cbt-recording-errors.log" 2>/dev/null | grep "$(date '+%Y-%m-%d')" | wc -l)
        echo -e "  PHP errors today: ${RECENT_ERRORS}"
    fi

    # Nginx error logs
    if [[ -f "/var/log/nginx/cbt-error.log" ]]; then
        NGINX_ERRORS=$(grep "$(date '+%Y/%m/%d')" "/var/log/nginx/cbt-error.log" 2>/dev/null | wc -l)
        echo -e "  Nginx errors today: ${NGINX_ERRORS}"
    fi

    # Check for upload-related errors
    UPLOAD_ERRORS=$(grep -i "upload\|body.*large\|timeout" /var/log/nginx/cbt-error.log 2>/dev/null | tail -5 | wc -l)
    if [[ $UPLOAD_ERRORS -gt 0 ]]; then
        echo -e "  ${YELLOW}Recent upload errors: ${UPLOAD_ERRORS}${NC}"
    fi
}

check_network_performance() {
    echo -e "${CYAN}[$(get_timestamp)] Network Performance:${NC}"

    # Network interface statistics
    if command -v iftop &> /dev/null; then
        # Get main network interface
        MAIN_IF=$(ip route | grep default | awk '{print $5}' | head -1)
        if [[ -n "$MAIN_IF" ]]; then
            RX_BYTES=$(cat "/sys/class/net/$MAIN_IF/statistics/rx_bytes")
            TX_BYTES=$(cat "/sys/class/net/$MAIN_IF/statistics/tx_bytes")
            RX_MB=$(( RX_BYTES / 1024 / 1024 ))
            TX_MB=$(( TX_BYTES / 1024 / 1024 ))
            echo -e "  Interface $MAIN_IF: RX ${RX_MB}MB, TX ${TX_MB}MB"
        fi
    fi

    # Check for network connectivity
    if ping -c 1 8.8.8.8 &> /dev/null; then
        echo -e "  Internet connectivity: ${GREEN}OK${NC}"
    else
        echo -e "  Internet connectivity: ${RED}FAILED${NC}"
    fi
}

generate_performance_report() {
    echo -e "${BLUE}=====================================================${NC}"
    echo -e "${BLUE}    Performance Summary${NC}"
    echo -e "${BLUE}=====================================================${NC}"

    # Get average values (simplified)
    CPU_AVG=$(top -bn1 | grep "Cpu(s)" | awk '{print $2}' | cut -d'%' -f1)
    MEM_INFO=$(free -m | grep '^Mem')
    MEM_PERCENT=$(( ($(echo $MEM_INFO | awk '{print $3}') * 100) / $(echo $MEM_INFO | awk '{print $2}') ))

    echo -e "CPU Usage: ${CPU_AVG}%"
    echo -e "Memory Usage: ${MEM_PERCENT}%"

    # Recommendations
    echo -e "\n${YELLOW}Recommendations:${NC}"

    if (( $(echo "$CPU_AVG > 80" | bc -l) )); then
        echo -e "  ${RED}⚠ High CPU usage detected${NC}"
    fi

    if [[ $MEM_PERCENT -gt 80 ]]; then
        echo -e "  ${RED}⚠ High memory usage detected${NC}"
    fi

    if [[ -d "$APP_DIR/storage/tmp" ]]; then
        OLD_FILES=$(find "$APP_DIR/storage/tmp" -type f -mmin +60 2>/dev/null | wc -l)
        if [[ $OLD_FILES -gt 10 ]]; then
            echo -e "  ${YELLOW}⚠ Clean old temporary files (${OLD_FILES} files > 1h old)${NC}"
        fi
    fi
}

cleanup_old_files() {
    echo -e "${YELLOW}[$(get_timestamp)] Cleaning up old files...${NC}"

    # Clean old temporary files (older than 2 hours)
    if [[ -d "$APP_DIR/storage/tmp" ]]; then
        find "$APP_DIR/storage/tmp" -type f -mmin +120 -delete 2>/dev/null
        echo -e "  Cleaned old temp files"
    fi

    # Rotate logs if they're too large (>100MB)
    for log_file in "/var/log/php/cbt-recording-errors.log" "/var/log/nginx/cbt-error.log"; do
        if [[ -f "$log_file" && $(stat -f%z "$log_file" 2>/dev/null || stat -c%s "$log_file" 2>/dev/null) -gt 104857600 ]]; then
            mv "$log_file" "${log_file}.old"
            touch "$log_file"
            chown www-data:www-data "$log_file" 2>/dev/null
            echo -e "  Rotated large log file: $log_file"
        fi
    done
}

# =====================================================
# MAIN MONITORING LOOP
# =====================================================

# Trap Ctrl+C to exit gracefully
trap 'echo -e "\n${GREEN}Monitoring stopped.${NC}"; exit 0' INT

counter=0
while true; do
    clear

    echo -e "${BLUE}CBT Recording Performance Monitor - $(get_timestamp)${NC}"
    echo -e "${BLUE}=====================================================${NC}"

    check_system_resources
    echo ""
    check_php_processes
    echo ""
    check_nginx_status
    echo ""
    check_storage_usage
    echo ""
    check_log_files
    echo ""
    check_network_performance
    echo ""

    # Generate report every 12 iterations (1 minute with 5s interval)
    if (( counter % 12 == 0 )); then
        generate_performance_report
        cleanup_old_files
    fi

    echo -e "${BLUE}=====================================================${NC}"
    echo -e "${BLUE}Next update in ${INTERVAL}s... (Iteration: $((counter + 1)))${NC}"

    sleep "$INTERVAL"
    ((counter++))
done
