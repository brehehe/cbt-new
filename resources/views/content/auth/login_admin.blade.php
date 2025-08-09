<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PRO CBT - Admin Login</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #C3D4EC 0%, #E8F0F9 100%);
            }

            /* Transition for password icon */
            .eye-icon {
                transition: all 0.2s ease;
            }

            .eye-icon-show path {
                transition: all 0.2s ease;
            }

            .eye-icon-show.hidden-password .eye-line {
                opacity: 1;
            }

            .eye-icon-show.visible-password .eye-line {
                opacity: 0;
            }
        </style>
    </head>

    <body class="min-h-screen">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div
                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl w-full max-w-md p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1E3A8A] to-[#C3D4EC]"></div>

                <!-- Logo & Welcome -->
                <div class="flex flex-col items-center mb-6">
                    <img src="{{ Vite::asset('resources/img/logo_m.png') }}" alt="PRO CBT Logo"
                        class="h-12 drop-shadow-md mb-4">
                    <h1 class="text-2xl font-bold text-[#1E3A8A]">Welcome Back!</h1>
                    <p class="text-gray-600 text-sm">Access your admin dashboard securely</p>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-4">
                    @csrf
                    <!-- Login Key -->
                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Login Key</label>
                        <div class="relative">
                            <input type="text" name="login_key"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Enter your login key">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <input type="text" name="username"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Enter your username">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Password with toggle -->
                    <div x-data="{ showPassword: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Enter your password">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200">
                                <svg class="w-5 h-5 eye-icon-show"
                                    :class="{ 'visible-password': showPassword, 'hidden-password': !showPassword }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <!-- Eye base - always visible -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <!-- Slash line that appears/disappears -->
                                    <path class="eye-line" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M3 3l18 18"
                                        :style="showPassword ? 'opacity: 0' : 'opacity: 1'" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Captcha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Captcha</label>
                        <div class="flex gap-3">
                            <input type="text" name="captcha"
                                class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Enter captcha">
                            <div
                                class="w-28 bg-gradient-to-r from-[#1E3A8A] to-[#C3D4EC] rounded-xl flex items-center justify-center p-[1px]">
                                <div class="w-full h-full bg-white rounded-xl flex items-center justify-center">
                                    <span class="text-[#1E3A8A] font-bold tracking-wider">ABC123</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-[#1E3A8A] focus:ring-[#1E3A8A]/20">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-[#1E3A8A] hover:text-[#2563EB] transition-colors">Forgot
                            password?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="button" id="loginButton"
                        class="w-full bg-gradient-to-r from-[#1E3A8A] to-[#2563EB] hover:from-[#1E3A8A] hover:to-[#1E3A8A] text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl mt-2">
                        Sign In to Dashboard
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>© 2024 PRO CBT. All rights reserved.</p>
                    <p class="mt-0.5">Secure login • Admin Portal</p>
                </div>
            </div>
        </div>

        <script>
            // Make sure Alpine.js is loaded via app.js
            document.addEventListener('alpine:init', () => {
                // Additional Alpine.js components can be initialized here if needed
            });

            // For prototype: Login button redirect to dashboard
            document.addEventListener('DOMContentLoaded', function() {
                const loginButton = document.getElementById('loginButton');

                loginButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    // For prototype, just redirect to dashboard route
                    // In a real app, you would validate and submit the form
                    window.location.href = "{{ route('admin.dashboard') }}";

                    // If you prefer using controller, uncomment this:
                    // window.location.href = "/admin/dashboard";
                });
            });
        </script>
    </body>

</html>
