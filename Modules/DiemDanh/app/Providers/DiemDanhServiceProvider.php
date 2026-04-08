<?php

namespace Modules\DiemDanh\Providers;

use Nwidart\Modules\ModulesServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class DiemDanhServiceProvider extends ModulesServiceProvider
{
    /**
     * The name of the module.
     */

    protected function registerServices(): void
    {
        // Có thể để trống cũng được
    }

    protected string $name = 'DiemDanh';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'diemdanh';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom(
            module_path($this->name, 'resources/views'),
            $this->nameLower
        );
    }
}
