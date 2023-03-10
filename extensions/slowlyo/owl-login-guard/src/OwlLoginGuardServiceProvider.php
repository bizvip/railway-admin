<?php

namespace Slowlyo\OwlLoginGuard;

use Slowlyo\OwlAdmin\Extend\ServiceProvider;
use Slowlyo\OwlAdmin\Renderers\NumberControl;
use Slowlyo\OwlAdmin\Renderers\TextControl;
use Slowlyo\OwlLoginGuard\Http\Middleware\LoginMiddleware;

class OwlLoginGuardServiceProvider extends ServiceProvider
{
    protected $middleware = [
        LoginMiddleware::class,
    ];

    public function register()
    {
        //
    }

    public function init()
    {
        parent::init();
        //

    }

    public function settingForm(): ?\Slowlyo\OwlAdmin\Renderers\Form
    {
        return $this->baseSettingForm()->data([
            'extension'     => $this->getName(),
            'max_try_count' => 10,
            'lock_time'     => 5,
        ])->body([
            TextControl::make()
                ->name('max_try_count')
                ->label(static::trans('login.max_try_count'))
                ->required(true)
                ->description(static::trans('login.max_try_count_description')),
            NumberControl::make()
                ->name('lock_time')
                ->label(static::trans('login.lock_time'))
                ->required(true)
                ->suffix(static::trans('login.minute'))
                ->min(1)
                ->displayMode('enhance'),
        ]);
    }

    public static function loginRestrictionCacheKey($username): string
    {
        return 'login-restriction-'.$username;
    }
}
