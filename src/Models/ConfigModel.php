<?php

namespace lz\admin\Models;


class ConfigModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sys_config';

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
