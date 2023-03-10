<?php

/******************************************************************************
 * Copyright (c) ArChang 2023.                                                *
 ******************************************************************************/

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Services\SliderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Renderers\ImageControl;
use Slowlyo\OwlAdmin\Renderers\NumberControl;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\TableColumn;
use Slowlyo\OwlAdmin\Renderers\TextControl;
use Slowlyo\OwlAdmin\Renderers\UUIDControl;

final class SliderController extends AdminController
{
    protected string $serviceName = SliderService::class;

    protected string $pageTitle = 'Slider';

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(): JsonResponse|JsonResource
    {
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->list());
        }

        return $this->response()->success($this->list());
    }

    /**
     * @return \Slowlyo\OwlAdmin\Renderers\Page
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->columns([
                TableColumn::make()->name('id')->label('ID')->sortable(true),
                TableColumn::make()->name('title')->label('标题'),
                TableColumn::make()
                    ->name('img')
                    ->label('图片')
                    ->type('image')
                    ->enlargeAble(true)
                    ->thumbRatio('16:9')
                    ->thumbMode('cover')
                    ->showToolbar(true),
                TableColumn::make()->name('link')->label('外链地址'),
                TableColumn::make()
                    ->name('weight')
                    ->label('排序(升序)')
                    ->sortable(true)
                    ->quickEdit(NumberControl::make()->min(0)),
                TableColumn::make()
                    ->name('created_at')
                    ->label('创建时间')
                    ->type('datetime')
                    ->sortable(true),
                TableColumn::make()
                    ->name('updated_at')
                    ->label('更新时间')
                    ->type('datetime')
                    ->sortable(true),
                $this->rowActions(true),
            ])
            ->loadDataOnce(true)
            ->quickSaveApi(admin_url('slider/quick'))
            ->orderBy('id')
            ->orderDir('desc')
            ->headerToolbar([
                $this->createButton(true),
                'bulkActions',
                amis('reload'),
                amis('filter-toggler')->align('right'),
            ]);

        return $this->baseList($crud);
    }

    public function form(): Form
    {
        return $this->baseForm()->body([
            TextControl::make()->name('title')->label('标题'),
            UUIDControl::make()->name('slug')->label('标识'),
            ImageControl::make()
                ->name('img')
                ->label('图片')
                ->receiver($this->uploadImagePath()),
            TextControl::make()->name('link')->label('外链地址'),
            TextControl::make()->name('weight')->label('排序(升序)'),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            TextControl::make()->static(true)->name('id')->label('ID'),
            TextControl::make()->static(true)->name('title')->label('标题'),
            TextControl::make()->static(true)->name('slug')->label('标识'),
            TextControl::make()
                ->name('img')
                ->label('图片')
                ->type('static-image')
                ->static(true)
                ->enlargeAble(true)
                ->thumbRatio('16:9')
                ->thumbMode('cover')
                ->showToolbar(false),
            TextControl::make()->static(true)->name('link')->label('外链地址'),
            TextControl::make()
                ->static(true)
                ->name('weight')
                ->label('排序(升序)'),
            TextControl::make()
                ->static(true)
                ->name('created_at')
                ->label('创建时间'),
            TextControl::make()
                ->static(true)
                ->name('updated_at')
                ->label('更新时间'),
        ]);
    }
}
