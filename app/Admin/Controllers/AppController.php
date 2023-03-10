<?php

/******************************************************************************
 * Copyright (c) ArChang 2023.                                                *
 ******************************************************************************/

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Services\AppService;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Slowlyo\OwlAdmin\Renderers\Component;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Renderers\ImageControl;
use Slowlyo\OwlAdmin\Renderers\NumberControl;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\SelectControl;
use Slowlyo\OwlAdmin\Renderers\TableColumn;
use Slowlyo\OwlAdmin\Renderers\TextControl;
use Slowlyo\OwlAdmin\Renderers\UUIDControl;

final class AppController extends AdminController
{
    protected string $serviceName = AppService::class;

    protected string $pageTitle = 'App';

    public function index(): JsonResponse|JsonResource
    {
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->list());
        }

        return $this->response()->success($this->list());
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->columns([
                TableColumn::make()->name('id')->label('ID')->sortable(true),

                TableColumn::make()
                    ->name('name')
                    ->label('名称')
                    ->unique(true)
                    ->searchable(true),

                TableColumn::make()
                    ->name('weight')
                    ->label('排序(升序)')
                    ->sortable(true)
                    ->quickEdit(NumberControl::make()->min(0),),

                TableColumn::make()
                    ->name('category_ids')
                    ->label('分类')
                    ->type('multi-select')
                    ->options(CategoryService::make()->getOptions())
                    ->remark('分类可以多选')
                    ->static(true),
                TableColumn::make()
                    ->name('img')
                    ->label('图片')
                    ->type('image')
                    ->enlargeAble(true)
                    ->thumbRatio('16:9')
                    ->thumbMode('cover')
                    ->showToolbar(true),
                // TableColumn::make()->name('slug')->label('唯一标识'),
                TableColumn::make()->name('link')->label('链接'),
                TableColumn::make()
                    ->name('created_at')
                    ->label('创建时间')
                    ->type('datetime'),
                TableColumn::make()
                    ->name('updated_at')
                    ->label('更新时间')
                    ->type('datetime')
                    ->sortable(true),
                $this->rowActions(true),
            ])
            ->loadDataOnce(true)
            ->quickSaveApi(admin_url('apps/quick'))
            ->orderBy('id')
            ->orderDir('desc')
            ->headerToolbar([
                $this->createButton(true),
                'bulkActions',
                Component::make()->setType('reload'),
            ]);

        return $this->baseList($crud);
    }

    public function form(): Form
    {
        return $this->baseForm()->body([
            UUIDControl::make()
                ->name('slug')
                ->label('唯一标识')
                ->required(true),
            NumberControl::make()
                ->name('weight')
                ->label('排序(升序)')
                ->min(0)
                ->max(4200000000)
                ->value(0),
            TextControl::make()
                ->name('name')
                ->label('名称')
                ->showCounter(true)
                ->minLength(2)
                ->maxLength(32)
                ->required(true)
                ->trimContents(true)
                ->clearValueOnEmpty(true),
            SelectControl::make()
                ->name('category_ids')
                ->label('分类')
                ->clearable(true)
                ->required(true)
                ->multiple(true)
                ->checkAll(true)
                ->options(CategoryService::make()->getOptions()),
            ImageControl::make()
                ->name('img')
                ->label('图片')
                ->limit(1024 * 1024 * 1024)
                ->accept('.jpg,.jpeg,.gif,.png,.bmp')
                ->receiver($this->uploadImagePath()),
            TextControl::make()
                ->name('link')
                ->label('链接')
                ->type('input-url')
                ->clearValueOnEmpty(true)
                ->trimContents(true),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            TextControl::make()->static(true)->name('id')->label('ID'),
            TextControl::make()
                ->static(true)
                ->name('weight')
                ->label('排序(升序)'),
            TextControl::make()->static(true)->name('name')->label('名称'),
            TextControl::make()
                ->static(true)
                ->name('category_ids')
                ->label('分类')
                ->type('multi-select')
                ->options(CategoryService::make()->getOptions()),
            TextControl::make()
                ->name('img')
                ->label('图片')
                ->type('static-image')
                ->static(true)
                ->enlargeAble(true)
                ->thumbRatio('16:9')
                ->thumbMode('cover')
                ->showToolbar(false),
            TextControl::make()->static(true)->name('slug')->label('唯一标识'),
            TextControl::make()
                ->static(true)
                ->name('link')
                ->label('链接')
                ->type('input-url'),
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
