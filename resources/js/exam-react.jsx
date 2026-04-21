import React from 'react';
import { createRoot } from 'react-dom/client';
import ExamContainer from './Exam/ExamContainer';
import AdminMonitorContainer from './Exam/Admin/AdminMonitorContainer';

import axios from 'axios';

// Get CSRF Token from meta tag
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

// 1. Student Exam Interface
const rootElement = document.getElementById('exam-app');
if (rootElement) {
    const userTimetableId = rootElement.getAttribute('data-user-timetable-id');
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <ExamContainer userTimetableId={userTimetableId} />
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