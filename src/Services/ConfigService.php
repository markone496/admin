<?php


namespace lz\admin\Services;


use Illuminate\Support\Facades\DB;
use lz\admin\Models\ConfigModel;
use lz\admin\Traits\ResTrait;

class ConfigService
{

    use ResTrait;

    /**
     * 刷新缓存
     * @param $index_key
     * @return bool
     */
    public static function refreshCache($index_key = null)
    {
        $key = CacheKeyService::SYS_CONFIG;
        $query = ConfigModel::query();
        $query->select([
            'id',
            'index_key',
            'data'
        ]);
        if ($index_key) {
            $query->where('index_key', $index_key);
            $data = $query->first()->toArray();
            RedisService::hset($key, $index_key, $data['data']);
        } else {
            $data = $query->get()->toArray();
            foreach ($data as $datum) {
                RedisService::hset($key, $datum['index_key'], $datum['data']);
            }
        }
        return true;
    }

    /**
     * 获取配置
     * @param $index_key
     * @return mixed
     */
    public static function getDataByIndexKey($index_key)
    {
        $key = CacheKeyService::SYS_CONFIG;
        $data = RedisService::hget($key, $index_key);
        if (!empty($data)) {
            return $data;
        }
        $query = ConfigModel::query();
        $query->select([
            'id',
            'index_key',
            'data'
        ]);
        $query->where('index_key', $index_key);
        $data = $query->first()->toArray();
        RedisService::hset($key, $index_key, $data['data']);
        return $data['data'];
    }

    /**
     * 表缓存和表字段缓存
     */
    public static function tableRefreshCache()
    {
        $tables = DB::select('SELECT table_name, table_comment
                      FROM information_schema.tables
                      WHERE table_schema = ?', [config('database.connections.mysql.database')]);
        $tablesArray = [];
        foreach ($tables as $table){
            $tablesArray[] = [
                'table_name' => $table->TABLE_NAME,
                'table_comment' => $table->TABLE_COMMENT
            ];
        }
        //存入缓存
        $key = CacheKeyService::SYS_TABLE;
        RedisService::set($key, $tablesArray);
        $key = CacheKeyService::SYS_TABLE_FIELD;
        RedisService::del($key);
        foreach ($tablesArray as $item) {
            $tableName = $item['table_name'];
            $columns = DB::select("SELECT column_name, column_comment
                       FROM information_schema.columns
                       WHERE table_name = ?
                       AND table_schema = ?", [$tableName, config('database.connections.mysql.database')]);
            $columnsArray = [];
            foreach ($columns as $column){
                $columnsArray[] = [
                    'column_name' => $column->COLUMN_NAME,
                    'column_comment' => $column->COLUMN_COMMENT
                ];
            }
            RedisService::hset($key, $tableName, $columnsArray);
        }
    }
}
