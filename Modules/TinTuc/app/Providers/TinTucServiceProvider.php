<?php

namespace Modules\TinTuc\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class TinTucServiceProvider extends ModuleServiceProvider
{
    protected function registerServices(): void
    {
        // có thể để trống
    }
    /**
     * The name of the module.
     */
    protected string $name = 'TinTuc';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'tintuc';

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
}
