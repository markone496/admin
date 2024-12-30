<?php


namespace lz\admin\Services;


use lz\admin\Traits\ResTrait;

class StaticDataService
{

    use ResTrait;

    /**
     * @var array 静态数据池
     */
    public static $_staticDataPool = [];

    /**
     * 获取静态数据
     * @param string $dsn 数据源名称
     * @param callable $setDataFunc 若未获取到静态数据，则调用此callback获取实时数据并写入到静态数据池
     * @return mixed
     */
    public static function getData($dsn, callable $setDataFunc = null)
    {
        if (empty(self::$_staticDataPool) || empty(self::$_staticDataPool[$dsn])) {
            if (!isset($setDataFunc)) {
                return null;
            }
            self::$_staticDataPool[$dsn] = $setDataFunc();
        }
        return self::$_staticDataPool[$dsn];
    }

    /**
     * 设置静态数据
     * @param string $dsn 数据源名称
     * @param mixed $data 要设置的静态数据，也可以是callable
     * @return mixed
     */
    public static function setData($dsn, $data)
    {
        if (!isset($data)) {
            return null;
        }
        if (is_callable($data)) {
            self::$_staticDataPool[$dsn] = $data();
        } else {
            self::$_staticDataPool[$dsn] = $data;
        }
        return self::$_staticDataPool[$dsn];
    }
}
