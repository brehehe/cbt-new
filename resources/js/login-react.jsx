import React from 'react';
import { createRoot } from 'react-dom/client';
import LoginUniversitas from './Auth/LoginUniversitas';
import axios from 'axios';

// Set up CSRF token for AJAX requests
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}
axios.defaults.headers.common['Accept'] = 'application/json';

const rootElement = document.getElementById('login-universitas-app');
if (rootElement) {
    const company = JSON.parse(rootElement.getAttribute('data-company') || '{}');
    const isCredentials = JSON.parse(rootElement.getAttribute('data-is-credentials') || 'false');
    const credentials = JSON.parse(rootElement.getAttribute('data-credentials') || '{}');
    const appWindows = rootElement.getAttribute('data-app-windows') || '';
    const appMac = rootElement.getAttribute('data-app-mac') || '';
    const appAndroid = rootElement.getAttribute('data-app-android') || '';
    const appIos = rootElement.getAttribute('data-app-ios') || '';
    
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <LoginUniversitas 
                company={company} 
                isCredentials={isCredentials} 
                credentials={credentials} 
                appWindows={appWindows}
                appMac={appMac}
                appAndroid={appAndroid}
                appIos={appIos}
            />
        </React.StrictMode>
    );
}
