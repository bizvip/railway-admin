<?php

namespace App\Services;

use App\Models\Slider;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * @method Slider getModel()
 * @method Slider|\Illuminate\Database\Query\Builder query()
 */
final class SliderService extends AdminService
{
    protected string $modelName = Slider::class;
}
