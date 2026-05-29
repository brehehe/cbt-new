import React from 'react';
import { createRoot } from 'react-dom/client';
import AdminDashboard from './Dashboard/AdminDashboard';
import axios from 'axios';

// Set up CSRF token for AJAX requests
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}
axios.defaults.headers.common['Accept'] = 'application/json';

const rootElement = document.getElementById('admin-dashboard-app');
if (rootElement) {
    const userProfile = JSON.parse(rootElement.getAttribute('data-user-profile') || '{}');
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <AdminDashboard userProfile={userProfile} />
        </React.StrictMode>
    );
}
