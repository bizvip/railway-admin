<?php

/******************************************************************************
 * Copyright (c) ArChang 2023.                                                *
 ******************************************************************************/

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Renderers\NumberControl;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\TableColumn;
use Slowlyo\OwlAdmin\Renderers\TextControl;

final class CategoryController extends AdminController
{
    protected string $serviceName = CategoryService::class;

    protected string $pageTitle = 'Category';

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
                TableColumn::make()->name('name')->label('中文名'),
                TableColumn::make()->name('slug')->label('标识'),
                TableColumn::make()->name('weight')->label('排序(升序)')->quickEdit(NumberControl::make()
                    ->min(0))->sortable(true),
                // TableColumn::make()->name('created_at')->label('创建时间')->type('datetime'),
                // TableColumn::make()->name('updated_at')->label('更新时间')->type('datetime'),
                $this->rowActions(true),
            ])
            ->loadDataOnce(true)
            ->quickSaveApi(admin_url('categories/quick'))
            ->orderBy('weight')
            ->orderDir('asc')
            ->headerToolbar([$this->createButton(true), $this->baseHeaderToolBar()]);

        return $this->baseList($crud);
    }

    public function form(): Form
    {
        return $this->baseForm()->body([
            TextControl::make()->name('name')->label('中文名'),
            TextControl::make()->name('slug')->label('标识'),
            TextControl::make()->name('weight')->label('排序(升序)'),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            TextControl::make()->static(true)->name('id')->label('ID'),
            TextControl::make()->static(true)->name('name')->label('中文名'),
            TextControl::make()->static(true)->name('slug')->label('标识'),
            TextControl::make()->static(true)->name('weight')->label('排序(升序)'),
            TextControl::make()->static(true)->name('created_at')->label('创建时间'),
            TextControl::make()->static(true)->name('updated_at')->label('更新时间'),
        ]);
    }
}
