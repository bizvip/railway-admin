<?php

/******************************************************************************
 * Copyright (c) ArChang 2023.                                                *
 ******************************************************************************/

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * @method Category getModel()
 * @method Category|\Illuminate\Database\Query\Builder query()
 */
final class CategoryService extends AdminService
{
    protected string $modelName = Category::class;

    public function getOptions(): ?array
    {
        $categories = (new self)->query()->select(['id', 'name'])->get()->toArray();
        if (!empty($categories)) {
            $keys   = \array_column($categories, 'id');
            $values = \array_column($categories, 'name');
            return \array_combine($keys, $values);
        }
        return null;
    }
}
