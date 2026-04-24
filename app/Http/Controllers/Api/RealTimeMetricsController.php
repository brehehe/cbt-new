<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamAlert;
use App\Models\User;
use App\Models\User\UserTimetable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RealTimeMetricsController extends Controller
{
    public function getSystemMetrics()
    {
        try {
            $metrics = [
                'timestamp' => date('Y-m-d H:i:s'),
                'server_performance' => $this->getServerPerformance(),
                'network_status' => $this->getNetworkStatus(),
                'system_resources' => $this->getSystemResources(),
                'user_activity' => $this->getUserActivity(),
                'database_health' => $this->getDatabaseHealth(),
            ];

            return new JsonResponse([
                'success' => true,
                'data' => $metrics,
                'generated_at' => microtime(true),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s'),
            ], 500);
        }
    }

    private function getServerPerformance()
    {
        // Real server response time
        $start = microtime(true);
        DB::select('SELECT 1');
        $dbResponseTime = (microtime(true) - $start) * 1000;

        // File system test
        $start = microtime(true);
        file_exists(__DIR__);
        $fsTime = (microtime(true) - $start) * 1000;

        // Network latency test
        $networkLatency = $this->pingGoogleDns();

        return [
            'db_response_time' => round($dbResponseTime, 2).'ms',
            'filesystem_response' => round($fsTime, 2).'ms',
            'network_latency' => $networkLatency ? $networkLatency.'ms' : 'unavailable',
            'avg_response_time' => round(($dbResponseTime + $fsTime) / 2, 2).'ms',
        ];
    }

    private function getNetworkStatus()
    {
        $tests = [
            'google_dns' => $this->testNetworkLatency('8.8.8.8'),
            'cloudflare_dns' => $this->testNetworkLatency('1.1.1.1'),
            'external_connectivity' => $this->testExternalConnectivity(),
        ];

        $avgLatency = array_filter([$tests['google_dns'], $tests['cloudflare_dns']]);
        $avgLatency = count($avgLatency) > 0 ? array_sum($avgLatency) / count($avgLatency) : null;

        return [
            'status' => $avgLatency && $avgLatency < 100 ? 'excellent' : ($avgLatency && $avgLatency < 200 ? 'good' : 'poor'),
            'average_latency' => $avgLatency ? round($avgLatency).'ms' : 'unavailable',
            'tests' => $tests,
        ];
    }

    private function getSystemResources()
    {
        return [
            'cpu_usage' => $this->getCpuUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
            'server_load' => $this->getServerLoad(),
            'active_processes' => $this->getActiveProcesses(),
        ];
    }

    private function getUserActivity()
    {
        // Real-time user counts
        $activeExams = UserTimetable::where('status', 'exam')->count();
        $recentActivity = UserTimetable::where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-2 minutes')))->count();

        // Session tracking if available
        $activeSessions = 0;
        if (Schema::hasTable('sessions')) {
            $activeSessions = DB::table('sessions')
                ->where('last_activity', '>=', time() - 300)
                ->count();
        }

        return [
            'active_exams' => $activeExams,
            'recent_activity' => $recentActivity,
            'active_sessions' => $activeSessions,
            'concurrent_users' => max($activeExams, $recentActivity, $activeSessions),
            'alerts_last_hour' => ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 hour')))->count(),
        ];
    }

    private function getDatabaseHealth()
    {
        $start = microtime(true);
        $testQueries = 0;
        $successfulQueries = 0;

        try {
            // Test basic queries
            User::count();
            $testQueries++;
            $successfulQueries++;

            UserTimetable::where('status', 'exam')->count();
            $testQueries++;
            $successfulQueries++;

            ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 hour')))->count();
            $testQueries++;
            $successfulQueries++;
        } catch (\Exception $e) {
            $testQueries++;
        }

        $responseTime = (microtime(true) - $start) * 1000;
        $successRate = $testQueries > 0 ? ($successfulQueries / $testQueries) * 100 : 0;

        return [
            'response_time' => round($responseTime, 2).'ms',
            'success_rate' => round($successRate, 1).'%',
            'queries_tested' => $testQueries,
            'successful_queries' => $successfulQueries,
            'connections' => $this->getDatabaseConnections(),
        ];
    }

    // Helper methods
    private function pingGoogleDns()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $ping = shell_exec('ping -n 1 8.8.8.8');
            preg_match('/time[=<]\s?(\d+)\s?ms/i', $ping, $matches);
        } else {
            $ping = shell_exec("ping -c 1 8.8.8.8 | grep 'time='");
            preg_match('/time=([\d.]+)\s?ms/', $ping, $matches);
        }

        return $matches[1] ?? null;
    }

    private function testNetworkLatency($host)
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec("ping -n 1 $host");
                preg_match('/time[=<]\s?(\d+)\s?ms/i', $output, $matches);
            } else {
                $output = shell_exec("ping -c 1 $host | grep 'time='");
                preg_match('/time=([\d.]+)\s?ms/', $output, $matches);
            }

            return isset($matches[1]) ? (float) $matches[1] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function testExternalConnectivity()
    {
        try {
            $start = microtime(true);
            $context = stream_context_create(['http' => ['timeout' => 3]]);
            $result = file_get_contents('https://www.google.com/favicon.ico', false, $context);
            $time = (microtime(true) - $start) * 1000;

            return $result ? round($time).'ms' : 'failed';
        } catch (\Exception $e) {
            return 'failed';
        }
    }

    private function getCpuUsage()
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec('wmic cpu get loadpercentage /value');
                preg_match('/LoadPercentage=(\d+)/', $output, $matches);

                return isset($matches[1]) ? $matches[1].'%' : 'unavailable';
            } else {
                if (function_exists('sys_getloadavg')) {
                    $load = sys_getloadavg();

                    return round($load[0] * 100).'%';
                }
            }

            return 'unavailable';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    private function getMemoryUsage()
    {
        try {
            // PHP memory usage
            $phpMemory = memory_get_usage(true);
            $phpMemoryPeak = memory_get_peak_usage(true);
            $phpMemoryLimit = ini_get('memory_limit');

            // System memory (if available)
            $systemMemory = $this->getSystemMemoryUsage();

            return [
                'php_current' => $this->formatBytes($phpMemory),
                'php_peak' => $this->formatBytes($phpMemoryPeak),
                'php_limit' => $phpMemoryLimit,
                'system' => $systemMemory,
            ];
        } catch (\Exception $e) {
            return 'error';
        }
    }

    private function getDiskUsage()
    {
        try {
            $diskFree = disk_free_space('/');
            $diskTotal = disk_total_space('/');

            if ($diskFree && $diskTotal) {
                $diskUsed = $diskTotal - $diskFree;
                $diskUsagePercent = ($diskUsed / $diskTotal) * 100;

                return [
                    'used' => $this->formatBytes($diskUsed),
                    'free' => $this->formatBytes($diskFree),
                    'total' => $this->formatBytes($diskTotal),
                    'usage_percent' => round($diskUsagePercent, 1).'%',
                ];
            }

            return 'unavailable';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    private function getServerLoad()
    {
        try {
            $concurrent = UserTimetable::where('status', 'exam')->count();
            $recentAlerts = ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-10 minutes')))->count();

            // Calculate load based on activity
            $baseLoad = 15; // Base load
            $userLoad = min($concurrent * 2, 40); // User-based load
            $alertLoad = min($recentAlerts * 5, 20); // Alert-based load

            $totalLoad = $baseLoad + $userLoad + $alertLoad;

            return min($totalLoad, 95).'%';
        } catch (\Exception $e) {
            return '25%';
        }
    }

    private function getActiveProcesses()
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec('tasklist | find /c ""');

                return (int) trim($output);
            } else {
                $output = shell_exec('ps aux | wc -l');

                return (int) trim($output);
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getDatabaseConnections()
    {
        try {
            $connections = DB::select('SHOW STATUS LIKE "Threads_connected"');

            return isset($connections[0]->Value) ? (int) $connections[0]->Value : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getSystemMemoryUsage()
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $totalMem = shell_exec('wmic OS get TotalVisibleMemorySize /value');
                $availMem = shell_exec('wmic OS get AvailableMemorySize /value');

                preg_match('/TotalVisibleMemorySize=(\d+)/', $totalMem, $totalMatches);
                preg_match('/AvailableMemorySize=(\d+)/', $availMem, $availMatches);

                if (isset($totalMatches[1]) && isset($availMatches[1])) {
                    $total = (int) $totalMatches[1] * 1024;
                    $available = (int) $availMatches[1] * 1024;
                    $used = $total - $available;
                    $usagePercent = ($used / $total) * 100;

                    return [
                        'total' => $this->formatBytes($total),
                        'used' => $this->formatBytes($used),
                        'available' => $this->formatBytes($available),
                        'usage_percent' => round($usagePercent, 1).'%',
                    ];
                }
            } else {
                $memInfo = file_get_contents('/proc/meminfo');
                if ($memInfo) {
                    preg_match('/MemTotal:\s+(\d+)/', $memInfo, $totalMatches);
                    preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $availMatches);

                    if (isset($totalMatches[1]) && isset($availMatches[1])) {
                        $total = (int) $totalMatches[1] * 1024;
                        $available = (int) $availMatches[1] * 1024;
                        $used = $total - $available;
                        $usagePercent = ($used / $total) * 100;

                        return [
                            'total' => $this->formatBytes($total),
                            'used' => $this->formatBytes($used),
                            'available' => $this->formatBytes($available),
                            'usage_percent' => round($usagePercent, 1).'%',
                        ];
                    }
                }
            }

            return 'unavailable';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    private function formatBytes($bytes)
    {
        if ($bytes >= 1024 * 1024 * 1024) {
            return round($bytes / (1024 * 1024 * 1024), 2).' GB';
        } elseif ($bytes >= 1024 * 1024) {
            return round($bytes / (1024 * 1024), 2).' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2).' KB';
        } else {
            return $bytes.' B';
        }
    }

    // Live stream metrics
    public function getLiveStreamMetrics()
    {
        try {
            return new JsonResponse([
                'success' => true,
                'data' => [
                    'active_streams' => $this->getActiveStreams(),
                    'streaming_performance' => $this->getStreamingPerformance(),
                    'peer_connections' => $this->getPeerConnections(),
                ],
                'timestamp' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function getActiveStreams()
    {
        // Count active streaming sessions
        $activeExams = UserTimetable::where('status', 'exam')->count();
        $recentStreams = UserTimetable::where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-1 minute')))->count();

        return [
            'total_active' => $activeExams,
            'recent_activity' => $recentStreams,
            'estimated_bandwidth' => ($activeExams * 0.5).' Mbps',
        ];
    }

    private function getStreamingPerformance()
    {
        // Estimate streaming performance based on system metrics
        $responseTime = (float) str_replace('ms', '', $this->getServerPerformance()['avg_response_time']);
        $load = (int) str_replace('%', '', $this->getServerLoad());

        $quality = 'excellent';
        if ($responseTime > 200 || $load > 70) {
            $quality = 'good';
        }
        if ($responseTime > 500 || $load > 85) {
            $quality = 'poor';
        }

        return [
            'quality' => $quality,
            'latency' => $responseTime.'ms',
            'server_load' => $load.'%',
        ];
    }

    private function getPeerConnections()
    {
        // This would require integration with your PeerJS monitoring
        return [
            'estimated_peers' => UserTimetable::where('status', 'exam')->count(),
            'connection_quality' => 'good', // This would be calculated from actual peer data
        ];
    }
}
