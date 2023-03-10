<?php

/******************************************************************************
 * Copyright (c) ArChang 2023.                                                *
 ******************************************************************************/

declare(strict_types=1);

namespace App\Services;

use App\Models\App;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * @method App getModel()
 * @method App|\Illuminate\Database\Query\Builder query()
 */
final class AppService extends AdminService
{
    protected string $modelName = App::class;

    public function store($data): bool
    {
        $columns = $this->getTableColumns();
        $model   = $this->getModel();

        foreach ($data as $k => $v) {
            if (!in_array($k, $columns)) {
                continue;
            }

            $model->setAttribute($k, $v);
        }

        return $model->save();
    }
}
