<?php

namespace lz\admin\Models;


class OptionModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sys_option';

    const CREATED_AT = null;

    protected $casts = [
        'option_config'  => 'array',
        'created_at'    => 'datetime:Y-m-d H:i:s',
        'updated_at'    => 'datetime:Y-m-d H:i:s',
    ];
}
