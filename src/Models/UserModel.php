<?php

namespace lz\admin\Models;


class UserModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sys_user';

    protected $casts = [
        'role_id' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
