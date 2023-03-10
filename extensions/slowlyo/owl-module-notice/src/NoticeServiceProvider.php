<?php

namespace Slowlyo\Notice;

use Slowlyo\OwlAdmin\Extend\ServiceProvider;

class NoticeServiceProvider extends ServiceProvider
{
    protected $menu = [
        [
            'title'    => '通知公告管理',
            'url'      => '/notice',
            'url_type' => '1',
            'icon'     => 'material-symbols:format-list-bulleted',
        ],
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
}
