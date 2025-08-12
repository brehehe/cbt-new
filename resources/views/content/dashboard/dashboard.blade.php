@extends('layout.app')
@section('content')
    <div class="min-h-screen p-6 bg-gradient-to-br from-[#F8FAFC] to-[#C3D4EC]/20">
        <!-- Welcome Section -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-[#3BA172]">Welcome back, Admin User!</h1>
            <p class="text-gray-600">Here's what's happening in your clinic today.</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Patients -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Patients</p>
                        <h3 class="text-2xl font-bold text-[#3BA172]">2,451</h3>
                        <p class="text-xs text-green-600">+12.5% from last month</p>
                    </div>
                    <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Today's Appointments</p>
                        <h3 class="text-2xl font-bold text-[#3BA172]">28</h3>
                        <p class="text-xs text-blue-600">8 pending confirmations</p>
                    </div>
                    <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Available Doctors -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Available Doctors</p>
                        <h3 class="text-2xl font-bold text-[#3BA172]">12</h3>
                        <p class="text-xs text-orange-600">3 on leave today</p>
                    </div>
                    <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Today's Revenue</p>
                        <h3 class="text-2xl font-bold text-[#3BA172]">Rp 8.5M</h3>
                        <p class="text-xs text-green-600">+5.25% from yesterday</p>
                    </div>
                    <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Upcoming Appointments -->
            <div class="lg:col-span-2 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100">
                <div class="p-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-[#3BA172]">Upcoming Appointments</h2>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <!-- Appointment 1 -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="bg-[#C3D4EC] rounded-full p-2">
                                    <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">John Doe</p>
                                    <p class="text-sm text-gray-500">General Checkup</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-[#3BA172]">09:30 AM</p>
                                <p class="text-xs text-gray-500">Dr. Sarah Smith</p>
                            </div>
                        </div>

                        <!-- Appointment 2 -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="bg-[#C3D4EC] rounded-full p-2">
                                    <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Emma Wilson</p>
                                    <p class="text-sm text-gray-500">Follow-up Consultation</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-[#3BA172]">10:15 AM</p>
                                <p class="text-xs text-gray-500">Dr. Michael Johnson</p>
                            </div>
                        </div>

                        <!-- Appointment 3 -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="bg-[#C3D4EC] rounded-full p-2">
                                    <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">James Brown</p>
                                    <p class="text-sm text-gray-500">Dental Cleaning</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-[#3BA172]">11:00 AM</p>
                                <p class="text-xs text-gray-500">Dr. Lisa Chen</p>
                            </div>
                        </div>

                        <!-- Appointment 4 -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="bg-[#C3D4EC] rounded-full p-2">
                                    <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Sophia Garcia</p>
                                    <p class="text-sm text-gray-500">Prenatal Checkup</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-[#3BA172]">01:30 PM</p>
                                <p class="text-xs text-gray-500">Dr. Robert Lee</p>
                            </div>
                        </div>

                        <!-- Appointment 5 -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="bg-[#C3D4EC] rounded-full p-2">
                                    <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">William Taylor</p>
                                    <p class="text-sm text-gray-500">Physical Therapy</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-[#3BA172]">02:45 PM</p>
                                <p class="text-xs text-gray-500">Dr. Amanda Kim</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Notifications -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 p-4">
                    <h2 class="text-lg font-semibold text-[#3BA172] mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="p-3 bg-[#C3D4EC]/20 rounded-lg text-[#3BA172] hover:bg-[#C3D4EC]/40 transition-colors">
                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-sm">New Patient</span>
                        </button>
                        <button class="p-3 bg-[#C3D4EC]/20 rounded-lg text-[#3BA172] hover:bg-[#C3D4EC]/40 transition-colors">
                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm">Schedule</span>
                        </button>
                        <button class="p-3 bg-[#C3D4EC]/20 rounded-lg text-[#3BA172] hover:bg-[#C3D4EC]/40 transition-colors">
                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="text-sm">Reports</span>
                        </button>
                        <button class="p-3 bg-[#C3D4EC]/20 rounded-lg text-[#3BA172] hover:bg-[#C3D4EC]/40 transition-colors">
                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm">Settings</span>
                        </button>
                    </div>
                </div>

                <!-- Recent Notifications -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 p-4">
                    <h2 class="text-lg font-semibold text-[#3BA172] mb-4">Recent Notifications</h2>
                    <div class="space-y-3">
                        <!-- Notification 1 -->
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-blue-100 rounded-full p-2">
                                <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">New appointment request</p>
                                <p class="text-xs text-gray-500">2 minutes ago</p>
                            </div>
                        </div>

                        <!-- Notification 2 -->
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-blue-100 rounded-full p-2">
                                <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Dr. Johnson updated patient record</p>
                                <p class="text-xs text-gray-500">15 minutes ago</p>
                            </div>
                        </div>

                        <!-- Notification 3 -->
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-blue-100 rounded-full p-2">
                                <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Medication inventory alert</p>
                                <p class="text-xs text-gray-500">45 minutes ago</p>
                            </div>
                        </div>

                        <!-- Notification 4 -->
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-blue-100 rounded-full p-2">
                                <svg class="w-4 h-4 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">System maintenance scheduled</p>
                                <p class="text-xs text-gray-500">1 hour ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
