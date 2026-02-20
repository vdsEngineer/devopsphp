<?php

namespace App\Providers;

use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class HealthServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        Health::checks([
            // Базовые проверки
            DatabaseCheck::new(),
            RedisCheck::new(),
            UsedDiskSpaceCheck::new()->warnWhenUsedSpaceIsAbovePercentage(80)->failWhenUsedSpaceIsAbovePercentage(90),
            PingCheck::new()->url('https://google.com')->label('Internet Connection'),

            // Проверки очередей
            QueueCheck::new()->name('Default Queue')->onQueue('default'),

            // Проверки кэша
            CacheCheck::new(),

            // Проверки окружения
            EnvironmentCheck::new()->expectEnvironment('production'),
            DebugModeCheck::new(),

            // Проверка расписания
            ScheduleCheck::new(),
        ]);
    }
}
