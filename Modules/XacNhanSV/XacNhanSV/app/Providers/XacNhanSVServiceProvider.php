<?php

namespace Modules\XacNhanSV\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class XacNhanSVServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'XacNhanSV';

    protected string $nameLower = 'xacnhansv';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
{
    parent::boot();
    // Đăng ký views với alias 'xacnhansv'
    // Thử cả 2 trường hợp Resources (hoa) và resources (thường)
    $path = is_dir(module_path('XacNhanSV', 'Resources/views')) 
            ? module_path('XacNhanSV', 'Resources/views')
            : module_path('XacNhanSV', 'resources/views');

    $this->loadViewsFrom($path, 'xacnhansv');
}
}