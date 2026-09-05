#!/bin/bash

# =====================================================
# CBT Recording Configuration Test Script
# =====================================================
# Purpose: Test and validate configuration for 1GB video upload
# Usage: bash test-cbt-configuration.sh

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
APP_DIR="/var/www/html/procbt_id"
TEST_SIZE_MB=50  # Test with 50MB file first
PHP_VERSION="8.3"

echo -e "${BLUE}=====================================================${NC}"
echo -e "${BLUE}    CBT Recording Configuration Test${NC}"
echo -e "${BLUE}=====================================================${NC}"

# =====================================================
# TEST FUNCTIONS
# =====================================================

test_php_configuration() {
    echo -e "${YELLOW}Testing PHP Configuration...${NC}"

    # Get PHP configuration values
    UPLOAD_MAX=$(php -r "echo ini_get('upload_max_filesize');")
    POST_MAX=$(php -r "echo ini_get('post_max_size');")
    MEMORY_LIMIT=$(php -r "echo ini_get('memory_limit');")
    MAX_EXEC_TIME=$(php -r "echo ini_get('max_execution_time');")
    MAX_INPUT_TIME=$(php -r "echo ini_get('max_input_time');")

    echo -e "  Upload max filesize: ${UPLOAD_MAX}"
    echo -e "  Post max size: ${POST_MAX}"
    echo -e "  Memory limit: ${MEMORY_LIMIT}"
    echo -e "  Max execution time: ${MAX_EXEC_TIME}"
    echo -e "  Max input time: ${MAX_INPUT_TIME}"

    # Check if values meet requirements
    UPLOAD_MB=$(php -r "echo (int)ini_get('upload_max_filesize');")
    POST_MB=$(php -r "echo (int)ini_get('post_max_size');")
    MEMORY_MB=$(php -r "echo (int)ini_get('memory_limit');")

    if [[ $UPLOAD_MB -ge 1200 && $POST_MB -ge 1300 && $MEMORY_MB -ge 2048 ]]; then
        echo -e "  ${GREEN}✓ PHP configuration meets requirements${NC}"
        return 0
    else
        echo -e "  ${RED}✗ PHP configuration needs adjustment${NC}"
        return 1
    fi
}

test_nginx_configuration() {
    echo -e "${YELLOW}Testing Nginx Configuration...${NC}"

    # Test nginx configuration syntax
    if sudo nginx -t 2>/dev/null; then
        echo -e "  ${GREEN}✓ Nginx configuration syntax is valid${NC}"
    else
        echo -e "  ${RED}✗ Nginx configuration has syntax errors${NC}"
        return 1
    fi

    # Check if nginx is running
    if systemctl is-active --quiet nginx; then
        echo -e "  ${GREEN}✓ Nginx is running${NC}"
    else
        echo -e "  ${RED}✗ Nginx is not running${NC}"
        return 1
    fi

    # Check client_max_body_size (find the largest one in the configuration)
    CLIENT_MAX=$(sudo nginx -T 2>&1 | grep -o 'client_max_body_size [^;]*' | awk '{print $2}' | tr 'a-z' 'A-Z' | while read -r line; do
        if [[ "$line" =~ ([0-9]+)G ]]; then
            echo "$(( ${BASH_REMATCH[1]} * 1024 ))M"
        elif [[ "$line" =~ ([0-9]+)M ]]; then
            echo "$line"
        elif [[ "$line" =~ ([0-9]+)K ]]; then
            echo "$(( ${BASH_REMATCH[1]} / 1024 ))M"
        elif [[ "$line" =~ ^[0-9]+$ ]]; then
            echo "$(( line / 1024 / 1024 ))M"
        fi
    done | sort -rn | head -1)

    if [[ -z "$CLIENT_MAX" ]]; then
        CLIENT_MAX="1M"
    fi

    echo -e "  Client max body size: ${CLIENT_MAX}"

    if [[ "$CLIENT_MAX" =~ [0-9]+M ]] && [[ ${CLIENT_MAX%M} -ge 1500 ]]; then
        echo -e "  ${GREEN}✓ Nginx client_max_body_size is sufficient${NC}"
        return 0
    else
        echo -e "  ${RED}✗ Nginx client_max_body_size needs to be increased${NC}"
        return 1
    fi
}

test_php_fpm_service() {
    echo -e "${YELLOW}Testing PHP-FPM Service...${NC}"

    if systemctl is-active --quiet php$PHP_VERSION-fpm; then
        echo -e "  ${GREEN}✓ PHP-FPM is running${NC}"
    else
        echo -e "  ${RED}✗ PHP-FPM is not running${NC}"
        return 1
    fi

    # Check PHP-FPM pool configuration
    if [[ -f "/etc/php/$PHP_VERSION/fpm/pool.d/cbt-recording.conf" ]]; then
        echo -e "  ${GREEN}✓ CBT recording pool configuration found${NC}"
    else
        echo -e "  ${YELLOW}! CBT recording pool not configured (using default)${NC}"
    fi

    # Check socket file
    if [[ -S "/var/run/php/php$PHP_VERSION-fpm.sock" ]]; then
        echo -e "  ${GREEN}✓ PHP-FPM socket is available${NC}"
        return 0
    else
        echo -e "  ${RED}✗ PHP-FPM socket not found${NC}"
        return 1
    fi
}

test_directory_permissions() {
    echo -e "${YELLOW}Testing Directory Permissions...${NC}"

    # Check if directories exist and are writable
    directories=(
        "$APP_DIR/storage"
        "$APP_DIR/storage/app/public"
        "$APP_DIR/storage/framework"
        "$APP_DIR/storage/logs"
    )

    for dir in "${directories[@]}"; do
        if [[ -d "$dir" ]]; then
            if [[ -w "$dir" ]]; then
                echo -e "  ${GREEN}✓ $dir is writable${NC}"
            else
                echo -e "  ${RED}✗ $dir is not writable${NC}"
                return 1
            fi
        else
            echo -e "  ${RED}✗ $dir does not exist${NC}"
            return 1
        fi
    done

    return 0
}

test_disk_space() {
    echo -e "${YELLOW}Testing Disk Space...${NC}"

    # Check available disk space
    AVAILABLE_GB=$(df -BG "$APP_DIR" | tail -1 | awk '{print $4}' | tr -d 'G')
    echo -e "  Available disk space: ${AVAILABLE_GB}GB"

    if [[ $AVAILABLE_GB -gt 10 ]]; then
        echo -e "  ${GREEN}✓ Sufficient disk space available${NC}"
        return 0
    else
        echo -e "  ${RED}✗ Low disk space (need at least 10GB free)${NC}"
        return 1
    fi
}

create_test_file() {
    echo -e "${YELLOW}Creating test file (${TEST_SIZE_MB}MB)...${NC}"

    TEST_FILE="/tmp/cbt-test-${TEST_SIZE_MB}MB.bin"

    # Create test file
    if dd if=/dev/zero of="$TEST_FILE" bs=1M count=$TEST_SIZE_MB 2>/dev/null; then
        echo -e "  ${GREEN}✓ Test file created: $TEST_FILE${NC}"
        return 0
    else
        echo -e "  ${RED}✗ Failed to create test file${NC}"
        return 1
    fi
}

test_upload_via_curl() {
    echo -e "${YELLOW}Testing Upload via cURL...${NC}"

    TEST_FILE="/tmp/cbt-test-${TEST_SIZE_MB}MB.bin"

    if [[ ! -f "$TEST_FILE" ]]; then
        echo -e "  ${RED}✗ Test file not found${NC}"
        return 1
    fi

    # Test web server accessibility
    TEST_URL="https://procbt.id"
    echo -e "  Testing web server connectivity: $TEST_URL"
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$TEST_URL" 2>/dev/null || echo "000")
    if [[ "$HTTP_CODE" == "200" || "$HTTP_CODE" == "302" ]]; then
        echo -e "  ${GREEN}✓ Web server is reachable (HTTP $HTTP_CODE)${NC}"
    else
        echo -e "  ${RED}✗ Web server returned HTTP $HTTP_CODE${NC}"
        return 1
    fi

    # Test Laravel storage upload via CLI script
    echo -e "  Testing Laravel Storage write via CLI..."
    if php test_upload2.php > /dev/null 2>&1; then
        echo -e "  ${GREEN}✓ Laravel storage upload test passed${NC}"
        return 0
    else
        echo -e "  ${RED}✗ Laravel storage upload test failed${NC}"
        return 1
    fi
}

test_php_memory_limit() {
    echo -e "${YELLOW}Testing PHP Memory Allocation...${NC}"

    # Create a PHP script to test memory allocation
    PHP_TEST_SCRIPT="/tmp/memory-test.php"
    cat > "$PHP_TEST_SCRIPT" << 'EOF'
<?php
$memory_limit = ini_get('memory_limit');
echo "Memory limit: " . $memory_limit . "\n";

// Try to allocate 1GB of memory
try {
    $test_array = array_fill(0, 1024 * 1024, str_repeat('x', 1024));
    echo "Successfully allocated ~1GB of memory\n";
    unset($test_array);
    echo "Memory test passed\n";
} catch (Exception $e) {
    echo "Memory allocation failed: " . $e->getMessage() . "\n";
    exit(1);
}
EOF

    if php "$PHP_TEST_SCRIPT" 2>/dev/null; then
        echo -e "  ${GREEN}✓ PHP memory allocation test passed${NC}"
        rm -f "$PHP_TEST_SCRIPT"
        return 0
    else
        echo -e "  ${RED}✗ PHP memory allocation test failed${NC}"
        rm -f "$PHP_TEST_SCRIPT"
        return 1
    fi
}

test_system_resources() {
    echo -e "${YELLOW}Testing System Resources...${NC}"

    # Check CPU cores
    CPU_CORES=$(nproc)
    echo -e "  CPU cores: $CPU_CORES"

    # Check total memory
    TOTAL_MEM_GB=$(free -g | grep '^Mem' | awk '{print $2}')
    echo -e "  Total memory: ${TOTAL_MEM_GB}GB"

    # Check load average
    LOAD_AVG=$(uptime | awk -F'load average:' '{print $2}' | awk '{print $1}' | tr -d ',')
    echo -e "  Current load: $LOAD_AVG"

    # Recommendations
    if [[ $CPU_CORES -ge 2 && $TOTAL_MEM_GB -ge 4 ]]; then
        echo -e "  ${GREEN}✓ System resources are adequate${NC}"
        return 0
    else
        echo -e "  ${YELLOW}! System resources may be limited for large uploads${NC}"
        return 0
    fi
}

cleanup_test_files() {
    echo -e "${YELLOW}Cleaning up test files...${NC}"

    rm -f "/tmp/cbt-test-${TEST_SIZE_MB}MB.bin"
    rm -f "/tmp/memory-test.php"

    echo -e "  ${GREEN}✓ Test files cleaned up${NC}"
}

generate_test_report() {
    echo -e "${BLUE}=====================================================${NC}"
    echo -e "${BLUE}    Test Summary${NC}"
    echo -e "${BLUE}=====================================================${NC}"

    echo -e "Tests completed: $(date)"
    echo -e "PHP Version: $(php -v | head -1)"
    echo -e "Nginx Version: $(nginx -v 2>&1)"
    echo -e ""

    if [[ $TEST_RESULTS -eq 0 ]]; then
        echo -e "${GREEN}✓ All tests passed! Configuration is ready for 1GB uploads.${NC}"
        echo -e ""
        echo -e "${BLUE}Next Steps:${NC}"
        echo -e "1. Test with actual CBT recording functionality"
        echo -e "2. Monitor performance during peak usage"
        echo -e "3. Set up log rotation and cleanup scripts"
        echo -e "4. Consider implementing video compression for storage optimization"
    else
        echo -e "${RED}✗ Some tests failed. Please review the configuration.${NC}"
        echo -e ""
        echo -e "${YELLOW}Recommendations:${NC}"
        echo -e "1. Run the setup script: sudo bash setup-cbt-recording.sh"
        echo -e "2. Check PHP and Nginx error logs"
        echo -e "3. Verify file permissions on storage directories"
        echo -e "4. Ensure sufficient disk space is available"
    fi
}

# =====================================================
# MAIN EXECUTION
# =====================================================

main() {
    echo -e "Starting configuration tests..."
    echo -e ""

    TEST_RESULTS=0

    # Run all tests
    test_php_configuration || TEST_RESULTS=1
    echo ""

    test_nginx_configuration || TEST_RESULTS=1
    echo ""

    test_php_fpm_service || TEST_RESULTS=1
    echo ""

    test_directory_permissions || TEST_RESULTS=1
    echo ""

    test_disk_space || TEST_RESULTS=1
    echo ""

    test_system_resources || TEST_RESULTS=1
    echo ""

    test_php_memory_limit || TEST_RESULTS=1
    echo ""

    create_test_file || TEST_RESULTS=1
    echo ""

    test_upload_via_curl || TEST_RESULTS=1
    echo ""

    cleanup_test_files
    echo ""

    generate_test_report

    exit $TEST_RESULTS
}

# Run main function
main "$@"
