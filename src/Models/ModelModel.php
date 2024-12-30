<?php

namespace lz\admin\Models;


class ModelModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sys_model';

    const CREATED_AT = null;

    protected $casts = [
        'table_config' => 'array',
        'cols_config' => 'array',
        'search_config' => 'array',
        'form_config' => 'array',
        'toolbar_config' => 'array',
        'tool_config' => 'array',
        'info_config' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
