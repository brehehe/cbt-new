#!/bin/bash

# CBT Recording Test Script
# Test complete recording functionality dari frontend ke backend

echo "=== CBT RECORDING FUNCTIONALITY TEST ==="
echo "Date: $(date)"
echo "Testing server recording capabilities..."
echo ""

echo "1. CHECKING PHP CONFIGURATION:"
echo "   Memory Limit: $(php -r "echo ini_get('memory_limit');")"
echo "   Upload Max: $(php -r "echo ini_get('upload_max_filesize');")"
echo "   Post Max: $(php -r "echo ini_get('post_max_size');")"
echo ""

echo "2. CHECKING STORAGE PERMISSIONS:"
cd /var/www/html/drshieldapp/cbt-new.drshieldapp.com
STORAGE_PERMS=$(ls -ld storage/app/public/exam_recordings/ | awk '{print $1}')
STORAGE_OWNER=$(ls -ld storage/app/public/exam_recordings/ | awk '{print $3":"$4}')
echo "   Directory: $STORAGE_PERMS $STORAGE_OWNER"

STORAGE_SIZE=$(du -sh storage/app/public/exam_recordings/ | awk '{print $1}')
FILE_COUNT=$(ls storage/app/public/exam_recordings/ | wc -l)
echo "   Storage Used: $STORAGE_SIZE ($FILE_COUNT files)"
echo ""

echo "3. CHECKING RECENT RECORDINGS:"
echo "   Latest 3 recordings:"
ls -lt storage/app/public/exam_recordings/ | head -4 | tail -3 | while read line; do
    echo "   $line"
done
echo ""

echo "4. CHECKING LARAVEL LOGS (Last 10 recording events):"
tail -50 storage/logs/laravel.log | grep -E "(saveRecordingVideo|Recording|Video)" | tail -10 | while read line; do
    echo "   $line"
done
echo ""

echo "5. CHECKING SYSTEM RESOURCES:"
echo "   Memory Usage: $(free -h | grep Mem | awk '{print $3"/"$2}')"
echo "   Disk Space: $(df -h /var/www/html | tail -1 | awk '{print $4" available ("$5" used)"}')"
echo "   Active PHP-FPM: $(ps aux | grep php-fpm | grep -v grep | wc -l) processes"
echo ""

echo "6. TESTING VIDEO UPLOAD CAPABILITY:"
# Create a test 10MB video file
TEST_FILE="/tmp/test_video_10mb.webm"
dd if=/dev/zero of="$TEST_FILE" bs=1M count=10 2>/dev/null
TEST_SIZE=$(ls -lh "$TEST_FILE" | awk '{print $5}')
echo "   Created test file: $TEST_SIZE"

# Test file write to storage
cp "$TEST_FILE" storage/app/public/exam_recordings/test_upload_$(date +%s).webm
if [ $? -eq 0 ]; then
    echo "   ✅ Storage write test: SUCCESS"
else
    echo "   ❌ Storage write test: FAILED"
fi
rm -f "$TEST_FILE"
echo ""

echo "7. CBT RECORDING STATUS:"
if [ $(ps aux | grep nginx | grep -v grep | wc -l) -gt 0 ] && [ $(ps aux | grep php-fpm | grep -v grep | wc -l) -gt 0 ]; then
    echo "   ✅ Services: nginx and php-fpm running"
else
    echo "   ❌ Services: Some services not running"
fi

# Check if recordings are happening in last 2 hours
RECENT_RECORDINGS=$(find storage/app/public/exam_recordings/ -name "*.webm" -newermt "2 hours ago" | wc -l)
echo "   📹 Recent recordings (2h): $RECENT_RECORDINGS files"

if [ $RECENT_RECORDINGS -gt 0 ]; then
    echo "   ✅ Recording system: ACTIVE"
else
    echo "   ⚠️ Recording system: No recent activity"
fi
echo ""

echo "=== TEST COMPLETED ==="
echo "Check above results for any issues."
echo ""
echo "EXPECTED RESULTS:"
echo "✅ PHP Memory: 2048M, Upload: 1200M, Post: 1300M"
echo "✅ Storage permissions: www-data writable"
echo "✅ Services running: nginx + php-fpm"
echo "✅ Recent recording activity visible in logs"
echo ""
echo "TROUBLESHOOTING:"
echo "- If upload test fails: Check storage permissions"
echo "- If no recent recordings: Check browser console for JS errors"
echo "- If services not running: systemctl restart nginx php8.3-fpm"
