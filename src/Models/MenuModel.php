<?php

namespace lz\admin\Models;


class MenuModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sys_menu';

    protected $casts = [
        'parent_ids' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
