<?php

namespace lz\admin\Models;


class FunctionModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sys_function';

    protected $casts = [
        'route' => 'array',
        'menu_ids' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
