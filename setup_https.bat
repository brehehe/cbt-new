@echo off
echo ========================================
echo    CBT Live Streaming Setup Script
echo ========================================
echo.

echo [1/4] Checking Laravel Herd installation...
where herd >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Laravel Herd not found!
    echo Please install Laravel Herd first:
    echo https://herd.laravel.com/windows
    echo.
    echo Or install via winget:
    echo winget install Laravel.Herd
    pause
    exit /b 1
)
echo ✓ Laravel Herd found

echo.
echo [2/4] Setting up HTTPS for cbt-test.test...
herd secure cbt-test
if %errorlevel% neq 0 (
    echo WARNING: Could not secure site. Trying to link first...
    cd /d "%~dp0"
    herd link cbt-test
    herd secure cbt-test
)

echo.
echo [3/4] Checking site status...
herd sites

echo.
echo [4/4] Setup complete!
echo.
echo ========================================
echo           IMPORTANT NOTES
echo ========================================
echo.
echo 1. Access your site via: https://cbt-test.test
echo 2. Browser will ask for camera permission - click ALLOW
echo 3. For live streaming to work:
echo    - Admin: Open https://cbt-test.test/admin/exam/live-stream
echo    - Student: Start exam at https://cbt-test.test
echo.
echo 4. If camera doesn't work:
echo    - Check browser permissions (click lock icon in address bar)
echo    - Try different browser (Chrome/Edge recommended)
echo    - Make sure no other app is using camera
echo.
echo ========================================
echo Ready to test live streaming!
echo ========================================
pause
