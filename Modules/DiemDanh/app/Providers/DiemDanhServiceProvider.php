<?php

namespace Modules\DiemDanh\Providers;

use Nwidart\Modules\ModulesServiceProvider;

class DiemDanhServiceProvider extends ModulesServiceProvider
{
    protected function registerServices(): void
    {
    }

    protected string $name = 'DiemDanh';

    protected string $nameLower = 'diemdanh';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom(
            module_path($this->name, 'resources/views'),
            $this->nameLower
        );
    }
}


