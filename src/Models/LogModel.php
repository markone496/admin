<?php

namespace lz\admin\Models;


class LogModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sys_log';

    const UPDATED_AT = null;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

}
