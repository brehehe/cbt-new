// Real-time system monitoring script
class RealTimeMonitor {
    constructor() {
        this.isActive = false;
        this.updateInterval = 5000; // 5 seconds
        this.intervalId = null;
        this.retryCount = 0;
        this.maxRetries = 3;

        // DOM elements
        this.elements = {
            responseTime: document.getElementById('real-response-time'),
            serverLoad: document.getElementById('real-server-load'),
            concurrentUsers: document.getElementById('real-concurrent-users'),
            networkStatus: document.getElementById('real-network-status'),
            systemUptime: document.getElementById('real-system-uptime'),
            memoryUsage: document.getElementById('real-memory-usage'),
            cpuUsage: document.getElementById('real-cpu-usage'),
            diskUsage: document.getElementById('real-disk-usage'),
            lastUpdate: document.getElementById('last-update-time'),
            connectionStatus: document.getElementById('connection-status')
        };

        this.init();
    }

    init() {
        console.log('🚀 Real-time monitor initialized');
        this.start();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Auto-refresh toggle
        const autoRefreshToggle = document.getElementById('auto-refresh-toggle');
        if (autoRefreshToggle) {
            autoRefreshToggle.addEventListener('change', (e) => {
                if (e.target.checked) {
                    this.start();
                } else {
                    this.stop();
                }
            });
        }

        // Manual refresh button
        const refreshButton = document.getElementById('manual-refresh-btn');
        if (refreshButton) {
            refreshButton.addEventListener('click', () => {
                this.fetchMetrics();
            });
        }

        // Update interval selector
        const intervalSelector = document.getElementById('update-interval');
        if (intervalSelector) {
            intervalSelector.addEventListener('change', (e) => {
                this.updateInterval = parseInt(e.target.value) * 1000;
                if (this.isActive) {
                    this.restart();
                }
            });
        }
    }

    start() {
        if (this.isActive) return;

        console.log('📊 Starting real-time monitoring...');
        this.isActive = true;
        this.fetchMetrics(); // Initial fetch
        this.intervalId = setInterval(() => {
            this.fetchMetrics();
        }, this.updateInterval);

        this.updateConnectionStatus('connected');
    }

    stop() {
        console.log('⏹️ Stopping real-time monitoring...');
        this.isActive = false;
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
        this.updateConnectionStatus('disconnected');
    }

    restart() {
        this.stop();
        setTimeout(() => this.start(), 100);
    }

    async fetchMetrics() {
        try {
            this.updateConnectionStatus('fetching');

            const response = await fetch('/api/metrics/system', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success) {
                this.updateUI(data.data);
                this.retryCount = 0;
                this.updateConnectionStatus('connected');
                console.log('✅ Metrics updated successfully');
            } else {
                throw new Error(data.error || 'Unknown error');
            }

        } catch (error) {
            console.error('❌ Error fetching metrics:', error);
            this.handleError(error);
        }
    }

    updateUI(data) {
        try {
            // Update last refresh time
            this.updateElement('lastUpdate', new Date().toLocaleTimeString());

            // Server Performance Metrics
            if (data.server_performance) {
                this.updateElement('responseTime', data.server_performance.avg_response_time);
                this.updateProgressBar('response-time-progress',
                    this.parseMetricValue(data.server_performance.avg_response_time), 200);
            }

            // System Resources
            if (data.system_resources) {
                this.updateElement('serverLoad', data.system_resources.server_load);
                this.updateElement('cpuUsage', data.system_resources.cpu_usage);
                this.updateElement('memoryUsage',
                    data.system_resources.memory_usage?.system?.usage_percent || 'N/A');
                this.updateElement('diskUsage',
                    data.system_resources.disk_usage?.usage_percent || 'N/A');

                // Update progress bars
                this.updateProgressBar('server-load-progress',
                    this.parseMetricValue(data.system_resources.server_load), 100);
                this.updateProgressBar('cpu-usage-progress',
                    this.parseMetricValue(data.system_resources.cpu_usage), 100);
            }

            // User Activity
            if (data.user_activity) {
                this.updateElement('concurrentUsers', data.user_activity.concurrent_users);
                this.updateBadge('active-exams-badge', data.user_activity.active_exams);
                this.updateBadge('recent-activity-badge', data.user_activity.recent_activity);
                this.updateBadge('alerts-badge', data.user_activity.alerts_last_hour);
            }

            // Network Status
            if (data.network_status) {
                this.updateElement('networkStatus', data.network_status.status);
                this.updateNetworkStatusBadge(data.network_status.status);

                if (data.network_status.average_latency) {
                    this.updateElement('networkLatency', data.network_status.average_latency);
                }
            }

            // Database Health
            if (data.database_health) {
                this.updateElement('dbResponseTime', data.database_health.response_time);
                this.updateElement('dbSuccessRate', data.database_health.success_rate);
                this.updateElement('dbConnections', data.database_health.connections);
            }

            // Update timestamp
            this.updateElement('metricsTimestamp', data.timestamp);

        } catch (error) {
            console.error('Error updating UI:', error);
        }
    }

    updateElement(elementKey, value) {
        const element = this.elements[elementKey] || document.getElementById(elementKey);
        if (element) {
            element.textContent = value;
            element.classList.add('updated');
            setTimeout(() => element.classList.remove('updated'), 500);
        }
    }

    updateProgressBar(elementId, value, maxValue) {
        const progressBar = document.getElementById(elementId);
        if (progressBar) {
            const percentage = Math.min((value / maxValue) * 100, 100);
            progressBar.style.width = percentage + '%';

            // Update color based on value
            progressBar.className = 'progress-bar';
            if (percentage > 80) {
                progressBar.classList.add('bg-danger');
            } else if (percentage > 60) {
                progressBar.classList.add('bg-warning');
            } else {
                progressBar.classList.add('bg-success');
            }
        }
    }

    updateBadge(elementId, value) {
        const badge = document.getElementById(elementId);
        if (badge) {
            badge.textContent = value;
            badge.classList.add('pulse');
            setTimeout(() => badge.classList.remove('pulse'), 500);
        }
    }

    updateNetworkStatusBadge(status) {
        const badge = document.getElementById('network-status-badge');
        if (badge) {
            badge.textContent = status;
            badge.className = 'badge';

            switch (status.toLowerCase()) {
                case 'excellent':
                    badge.classList.add('badge-success');
                    break;
                case 'good':
                    badge.classList.add('badge-primary');
                    break;
                case 'poor':
                    badge.classList.add('badge-danger');
                    break;
                default:
                    badge.classList.add('badge-warning');
            }
        }
    }

    updateConnectionStatus(status) {
        const statusElement = this.elements.connectionStatus;
        if (statusElement) {
            statusElement.className = 'connection-status';

            switch (status) {
                case 'connected':
                    statusElement.classList.add('connected');
                    statusElement.textContent = '🟢 Connected';
                    break;
                case 'fetching':
                    statusElement.classList.add('fetching');
                    statusElement.textContent = '🔄 Updating...';
                    break;
                case 'error':
                    statusElement.classList.add('error');
                    statusElement.textContent = '🔴 Connection Error';
                    break;
                case 'disconnected':
                    statusElement.classList.add('disconnected');
                    statusElement.textContent = '⚫ Monitoring Stopped';
                    break;
            }
        }
    }

    handleError(error) {
        this.retryCount++;
        this.updateConnectionStatus('error');

        console.error(`Retry ${this.retryCount}/${this.maxRetries}:`, error.message);

        if (this.retryCount >= this.maxRetries) {
            console.error('Max retries reached, stopping monitoring');
            this.stop();
            this.showErrorNotification('Real-time monitoring stopped due to connection errors');
        } else {
            // Exponential backoff
            const retryDelay = Math.pow(2, this.retryCount) * 1000;
            console.log(`Retrying in ${retryDelay}ms...`);

            setTimeout(() => {
                if (this.isActive) {
                    this.fetchMetrics();
                }
            }, retryDelay);
        }
    }

    parseMetricValue(valueString) {
        if (typeof valueString === 'number') return valueString;
        const matches = valueString.match(/\d+(\.\d+)?/);
        return matches ? parseFloat(matches[0]) : 0;
    }

    showErrorNotification(message) {
        // Show error notification (implement based on your notification system)
        console.error('NOTIFICATION:', message);

        // Example using alert (replace with your preferred notification system)
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Monitoring Error',
                text: message,
                confirmButtonText: 'Retry',
                showCancelButton: true,
                cancelButtonText: 'Stop'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.retryCount = 0;
                    this.start();
                }
            });
        }
    }

    // Public methods for external control
    getStatus() {
        return {
            isActive: this.isActive,
            updateInterval: this.updateInterval,
            retryCount: this.retryCount
        };
    }

    setUpdateInterval(seconds) {
        this.updateInterval = seconds * 1000;
        if (this.isActive) {
            this.restart();
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initializing Real-Time Monitor...');

    // Global monitor instance
    window.realTimeMonitor = new RealTimeMonitor();

    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        .updated {
            background-color: #28a745 !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .pulse {
            animation: pulse 0.5s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .connection-status {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .connection-status.connected {
            background-color: #d4edda;
            color: #155724;
        }

        .connection-status.fetching {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .connection-status.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .connection-status.disconnected {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }
    `;
    document.head.appendChild(style);

    console.log('✅ Real-Time Monitor ready!');
});

// Export for module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RealTimeMonitor;
}
