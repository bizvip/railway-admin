<?php

namespace Slowlyo\Notice\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

class Notice extends Model
{
    use SoftDeletes;

    /** @var string 通知 */
    const TYPE_NOTICE = 'NOTICE';
    /** @var string 公告 */
    const TYPE_ANNOUNCEMENT = 'ANNOUNCEMENT';

    /** @var string 显示 */
    const STATE_SHOW = 1;
    /** @var string 隐藏 */
    const STATE_HIDE = 0;
}
