import React from 'react';
import { createRoot } from 'react-dom/client';
import ExamContainer from './Exam/ExamContainer';
import AdminMonitorContainer from './Exam/Admin/AdminMonitorContainer';
import QuestionNavDemo from './Exam/QuestionNavDemo';

import axios from 'axios';

// Get CSRF Token from meta tag
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

// Wajib: agar Laravel mengembalikan 401 JSON (bukan redirect 302 ke /login)
// saat session dihapus/expired. Tanpa ini, Laravel redirect ke /login dan
// interceptor 401 di useLiveSession.js tidak pernah terpicu.
axios.defaults.headers.common['Accept'] = 'application/json';

// 1. Student Exam Interface
const rootElement = document.getElementById('exam-app');
if (rootElement) {
    const userTimetableId = rootElement.getAttribute('data-user-timetable-id');
    const colorPrimary = rootElement.getAttribute('data-color-primary') || '#1e3a5f';
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <ExamContainer userTimetableId={userTimetableId} defaultCompanyColor={colorPrimary} />
        </React.StrictMode>
    );
}

// 2. Admin Monitoring Dashboard
const monitorElement = document.getElementById('admin-monitor-app');
if (monitorElement) {
    const timetableId = monitorElement.getAttribute('data-timetable-id');
    const root = createRoot(monitorElement);
    root.render(
        <React.StrictMode>
            <AdminMonitorContainer timetableId={timetableId} />
        </React.StrictMode>
    );
}

// 3. Exam Navigation Demo Sandbox
const demoElement = document.getElementById('exam-nav-demo-app');
if (demoElement) {
    const root = createRoot(demoElement);
    root.render(
        <React.StrictMode>
            <QuestionNavDemo />
        </React.StrictMode>
    );
}