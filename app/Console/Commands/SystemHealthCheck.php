<?php

namespace App\Console\Commands;

use App\Repositories\HealthCheckRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SystemHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:system-health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run system health';

    /**
     * Execute the console command.
     */

    protected $healthCheckRepository;

    public const THRESHOLD = 0.8;

    public function __construct(HealthCheckRepository $healthCheckRepository)
    {
        parent::__construct();
        $this->healthCheckRepository = $healthCheckRepository;
    }

    public function handle()
    {
        $checkDatabaseConnectionData = $this->checkDatabaseConnection();
        $checkHighCPUUsageData = $this->checkHighCPUUsage();

        $this->healthCheckRepository->insert([
            $checkDatabaseConnectionData,
            $checkHighCPUUsageData
        ]);
    }

    public function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo(); 
            return [
                'status' => 'OK',
                'details' => 'Database connection success'
            ]; 
        } catch (\Exception $e) {
            return [
                'status' => 'Error',
                'details' => 'Database connection lost'
            ]; 
        }
    }

    public function checkHighCPUUsage()
    {
        $load = sys_getloadavg(); 
        $cpuLoad = $load[0];

        if ($cpuLoad < self::THRESHOLD) {
            return [
                'status' => 'OK',
                'details' => 'CPU usage within normal parameters'
            ]; 
        };

        return [
            'status' => 'Warning',
            'details' => 'High CPU usage'
        ]; 
    }

}
