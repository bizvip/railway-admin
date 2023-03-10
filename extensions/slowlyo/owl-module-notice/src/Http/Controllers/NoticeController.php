<?php

namespace Slowlyo\Notice\Http\Controllers;

use Illuminate\Http\Request;
use Slowlyo\OwlAdmin\Renderers\Tpl;
use Slowlyo\OwlAdmin\Renderers\Mapping;
use Slowlyo\Notice\Models\Notice as Model;
use Slowlyo\Notice\NoticeServiceProvider;
use Slowlyo\Notice\Services\NoticeService;
use Slowlyo\OwlAdmin\Renderers\TextControl;
use Slowlyo\OwlAdmin\Renderers\TableColumn;
use Slowlyo\OwlAdmin\Renderers\FormControl;
use Slowlyo\OwlAdmin\Renderers\RadiosControl;
use Slowlyo\OwlAdmin\Renderers\NumberControl;
use Slowlyo\OwlAdmin\Renderers\SwitchControl;
use Slowlyo\OwlAdmin\Renderers\SchemaPopOver;
use Slowlyo\OwlAdmin\Renderers\SelectControl;
use Slowlyo\OwlAdmin\Renderers\RichTextControl;
use Slowlyo\OwlAdmin\Controllers\AdminController;

class NoticeController extends AdminController
{
    protected string $serviceName = NoticeService::class;

    protected string $queryPath = 'notice';

    protected \Slowlyo\OwlAdmin\Services\AdminService|NoticeService $service;

    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = $this->trans('page_title');
    }

    public function index()
    {
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->list());
        }

        return $this->response()->success($this->list());
    }

    public function list()
    {
        $crud = $this->baseCRUD()
            ->quickSaveItemApi(admin_url('notice_quick_edit'))
            ->headerToolbar([
                $this->createButton(true, 'lg'),
                ...$this->baseHeaderToolBar(),
            ])
            ->filter(
                $this->baseFilter()->body([
                    TextControl::make()->name('title')->label($this->trans('title'))->size('md'),
                    SelectControl::make()
                        ->name('type')
                        ->label($this->trans('type'))
                        ->size('md')
                        ->options($this->service->getType()),
                    SelectControl::make()
                        ->name('state')
                        ->label($this->trans('state'))
                        ->size('md')
                        ->options($this->service->getState()),
                ])
            )
            ->columns([
                TableColumn::make()->name('id')->label('ID')->sortable(true),
                TableColumn::make()
                    ->name('title')
                    ->label($this->trans('title'))
                    ->type('tpl')
                    ->tpl('${title | truncate: 24}')
                    ->popOver(
                        SchemaPopOver::make()->trigger('hover')->body(Tpl::make()->tpl('${title}'))
                    ),
                TableColumn::make()
                    ->name('type')
                    ->label($this->trans('type'))
                    ->type('mapping')
                    ->map($this->typeMapping()),
                TableColumn::make()->name('weight')->label($this->trans('weight'))->sortable(true)->quickEdit(
                    NumberControl::make()->displayMode('enhance')->set('saveImmediately', true)
                ),
                TableColumn::make()
                    ->name('state')
                    ->label($this->trans('state'))
                    ->quickEdit(
                        SwitchControl::make()->mode('inline')
                            ->onText($this->service->getState(Model::STATE_SHOW))
                            ->offText($this->service->getState(Model::STATE_HIDE))
                            ->set('saveImmediately', true)
                    ),
                TableColumn::make()
                    ->name('created_at')
                    ->label(__('admin.created_at'))
                    ->type('datetime')
                    ->sortable(true),
                TableColumn::make()
                    ->name('updated_at')
                    ->label(__('admin.updated_at'))
                    ->type('datetime')
                    ->sortable(true),
                $this->rowActions(true, 'lg')->set('width', 200),
            ]);

        return $this->baseList($crud);
    }

    public function form()
    {
        return $this->baseForm()->data([
            'type'   => Model::TYPE_NOTICE,
            'weight' => 0,
            'state'  => Model::STATE_SHOW,
        ])->body([
            TextControl::make()->name('title')->label($this->trans('title'))->required(true)->maxLength(255),
            RadiosControl::make()->name('type')->label($this->trans('type'))->options($this->service->getType()),
            NumberControl::make()
                ->name('weight')
                ->label($this->trans('weight'))
                ->displayMode('enhance')
                ->required(true),
            SwitchControl::make()
                ->name('state')
                ->label($this->trans('state'))
                ->onText($this->service->getState(Model::STATE_SHOW))
                ->offText($this->service->getState(Model::STATE_HIDE)),
            RichTextControl::make()->name('content')->label($this->trans('content'))->required(true),
        ]);
    }

    public function detail($id)
    {
        $staticText = fn($name, $label) => TextControl::make()->static(true)->name($name)->label($label);

        return $this->baseDetail($id)->body([
            $staticText('id', 'ID'),
            $staticText('title', $this->trans('title')),
            FormControl::make()->static(true)->label($this->trans('type'))->body(
                Mapping::make()->name('type')->map($this->typeMapping())
            ),
            $staticText('weight', $this->trans('weight')),
            FormControl::make()->label($this->trans('state'))->body(
                Mapping::make()->name('state')->map([
                    Model::STATE_SHOW => $this->label($this->service->getState(Model::STATE_SHOW), 'success'),
                    Model::STATE_HIDE => $this->label($this->service->getState(Model::STATE_HIDE), 'warning'),
                ])
            ),
            FormControl::make()->label($this->trans('content'))->body(Tpl::make()->tpl('${content | raw}')),
            $staticText('created_at', __('admin.created_at')),
            $staticText('updated_at', __('admin.updated_at')),
        ]);
    }

    /**
     * 快速编辑
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function quickEdit(Request $request)
    {
        $primaryKey = $request->input('id');
        $data       = $request->only(['state', 'weight']);

        $result = $this->service->update($primaryKey, $data);

        return $this->autoResponse($result, __('admin.save'));
    }

    private function typeMapping()
    {
        return [
            Model::TYPE_NOTICE       => $this->label($this->service->getType(Model::TYPE_NOTICE), 'primary'),
            Model::TYPE_ANNOUNCEMENT => $this->label($this->service->getType(Model::TYPE_ANNOUNCEMENT), 'success'),
        ];
    }

    private function label($value, $type)
    {
        return "<span class='label label-{$type}'>{$value}</span>";
    }

    private function trans($key)
    {
        return NoticeServiceProvider::trans('notice.' . $key);
    }
}
