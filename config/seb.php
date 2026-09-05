<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Safe Exam Browser Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Safe Exam Browser integration
    |
    */

    // Enable/disable SEB requirement for exams
    'enabled' => env('SEB_ENABLED', false),

    // Require all exams to use SEB
    'require_for_all_exams' => env('SEB_REQUIRE_ALL', false),

    // Browser Exam Key (BEK) for validation
    'browser_exam_key' => env('SEB_BROWSER_EXAM_KEY', null),

    // Require Browser Exam Key validation
    'require_browser_exam_key' => env('SEB_REQUIRE_BEK', false),

    // Config Key for .seb file
    'config_key' => env('SEB_CONFIG_KEY', null),

    // Quit password (for exiting SEB)
    'quit_password' => env('SEB_QUIT_PASSWORD', 'admin123'),

    // Allowed user agents
    'allowed_user_agents' => [
        'SEB/',
        'SafeExamBrowser',
    ],

    // SEB Configuration defaults
    'config_defaults' => [
        'allowQuit' => true,
        'quitURLConfirm' => true,
        'examSessionClearCookiesOnEnd' => true,
        'examSessionClearCookiesOnStart' => false,
        'browserScreenKeyboard' => false,
        'enableTouchExit' => false,
        'allowSpellCheck' => false,
        'allowDictionaryLookup' => false,
        'blockPopUpWindows' => true,
        'newBrowserWindowByLinkPolicy' => 2,
        'enablePlugIns' => false,
        'allowVideoCapture' => true,
        'allowAudioCapture' => true,
        'enableZoomText' => true,
        'enableZoomPage' => true,
        'allowPreferencesWindow' => false,
        'showReloadButton' => true,
        'showNavigationButtons' => false,
    ],
];
