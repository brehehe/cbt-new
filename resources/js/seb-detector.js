/**
 * Safe Exam Browser Detector
 * Detects if the application is running inside Safe Exam Browser
 */
export class SEBDetector {
    constructor() {
        this.isSEB = this.detectSEB();
        this.init();
    }

    /**
     * Initialize detector
     */
    init() {
        if (this.isSEB) {
            console.log('✅ Safe Exam Browser detected');
            this.addSEBIndicator();
        } else {
            console.log('ℹ️ Running in normal browser');
        }
    }

    /**
     * Detect if running in Safe Exam Browser
     */
    detectSEB() {
        const userAgent = navigator.userAgent;

        // Check user agent
        if (userAgent.includes('SEB/') || userAgent.includes('SafeExamBrowser')) {
            return true;
        }

        // Check for SEB-specific objects (if available)
        if (window.SEB || window.SafeExamBrowser) {
            return true;
        }

        return false;
    }

    /**
     * Validate SEB with server
     */
    async validateWithServer() {
        try {
            const response = await fetch('/seb/validate');
            const data = await response.json();
            console.log('SEB Validation:', data);
            return data.valid;
        } catch (error) {
            console.error('SEB validation error:', error);
            return false;
        }
    }

    /**
     * Check if specific timetable requires SEB
     */
    async checkTimetableRequiresSEB(timetableId) {
        try {
            const response = await fetch(`/seb/check/${timetableId}`);
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('SEB timetable check error:', error);
            return { require_seb: false };
        }
    }

    /**
     * Add visual indicator that SEB is active
     */
    addSEBIndicator() {
        // Check if indicator already exists
        if (document.getElementById('seb-indicator')) {
            return;
        }

        const indicator = document.createElement('div');
        indicator.id = 'seb-indicator';
        indicator.innerHTML = `
            <div style="
                position: fixed;
                top: 10px;
                right: 10px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 8px 16px;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 600;
                z-index: 9999;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                gap: 8px;
            ">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span>Protected Mode (SEB)</span>
            </div>
        `;
        document.body.appendChild(indicator);
    }

    /**
     * Show SEB required warning
     */
    showSEBRequired(timetableName = 'This exam', downloadUrl = null) {
        const message = `
            <div style="
                max-width: 600px;
                margin: 50px auto;
                padding: 30px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            ">
                <div style="text-align: center; margin-bottom: 20px;">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" style="margin: 0 auto;">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <h2 style="color: #1f2937; margin-bottom: 10px; text-align: center;">
                    ⚠️ Safe Exam Browser Diperlukan
                </h2>
                <p style="color: #6b7280; margin-bottom: 20px; text-align: center;">
                    ${timetableName} harus diakses menggunakan Safe Exam Browser.
                </p>

                <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin: 20px 0;">
                    <strong style="color: #1f2937; display: block; margin-bottom: 10px;">Langkah-langkah:</strong>
                    <ol style="color: #4b5563; margin: 0; padding-left: 20px;">
                        <li style="margin-bottom: 8px;">Download file konfigurasi SEB di bawah</li>
                        <li style="margin-bottom: 8px;">Buka file tersebut (akan membuka Safe Exam Browser)</li>
                        <li>Ujian akan otomatis dimulai dalam mode aman</li>
                    </ol>
                </div>

                ${downloadUrl ? `
                    <a href="${downloadUrl}" style="
                        display: block;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        text-align: center;
                        padding: 12px 24px;
                        border-radius: 8px;
                        text-decoration: none;
                        font-weight: 600;
                        margin-top: 20px;
                    ">
                        📥 Download Konfigurasi SEB
                    </a>
                ` : ''}
            </div>
        `;

        return message;
    }

    /**
     * Block access if SEB required but not detected
     */
    blockAccessIfRequired(requireSEB, timetableId, timetableName) {
        if (requireSEB && !this.isSEB) {
            const downloadUrl = `/seb/config/${timetableId}`;
            const warningHtml = this.showSEBRequired(timetableName, downloadUrl);

            // Replace page content
            document.body.innerHTML = warningHtml;

            return true; // Access blocked
        }

        return false; // Access allowed
    }
}

// Export singleton instance
export const sebDetector = new SEBDetector();

// Make it available globally for Livewire components
window.sebDetector = sebDetector;
