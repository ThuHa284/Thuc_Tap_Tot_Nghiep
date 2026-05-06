<?php

namespace Modules\ThiTracNghiem\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ThiTracNghiemServiceProvider extends ModuleServiceProvider
{
    // ✅ BẮT BUỘC phải có
    protected function registerServices(): void
    {
        // có thể để trống
    }
    public function boot(): void
    {
        parent::boot(); // 🔥 BẮT BUỘC

        $this->loadViewsFrom(
            module_path('ThiTracNghiem', 'resources/views'),
            'thitracnghiem'
        );
    }
    /**
     * The name of the module.
     */
    protected string $name = 'ThiTracNghiem';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'thitracnghiem';

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
