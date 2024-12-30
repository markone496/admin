<?php

namespace lz\admin\Models;


class RoleModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sys_role';

    protected $casts = [
        'function_ids' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
