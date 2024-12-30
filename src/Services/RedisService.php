<?php


namespace lz\admin\Services;


use Illuminate\Support\Facades\Redis;
use lz\admin\Traits\ResTrait;

class RedisService
{

    use ResTrait;

    public static function del($key)
    {
        return Redis::del($key);
    }

    public static function keys($key)
    {
        return Redis::keys($key);
    }

    public static function set($key, $value, $expire = 0)
    {
        $value = serialize($value);
        $result = Redis::set($key, $value);
        if ($result && $expire > 0) {
            $result = Redis::expire($key, $expire);
        }
        return $result;
    }

    public static function get($key)
    {
        $value = Redis::get($key);
        return unserialize($value);
    }

    public static function hset($key, $index, $value)
    {
        $value = serialize($value);
        return Redis::hset($key, $index, $value);
    }

    public static function hget($key, $index)
    {
        $value = Redis::hget($key, $index);
        return unserialize($value);
    }

    public static function hKeys($key)
    {
        $value = Redis::hKeys($key);
        return (array)$value;
    }

    public static function hgetAll($key)
    {
        $value = Redis::hgetall($key);
        foreach ($value as &$item) {
            $item = unserialize($item);
        }
        return (array)$value;
    }

    public static function hdel($key, $index)
    {
        return Redis::hdel($key, $index);
    }

    /**
     * 事件锁
     * @param $lockKey
     * @param $callback
     * @return array
     */
    public static function lock($lockKey, $callback)
    {
        if (!Redis::setnx($lockKey, 1, ['nx', 'ex' => 10])) {
            return self::error('操作频繁');
        }
        try {
            //todo 处理任务
            $result = $callback();
            //todo 删除锁
            Redis::del($lockKey);
            //todo 返回结果
            return $result;
        } catch (\Throwable $exception) {
            //todo 删除锁
            Redis::del($lockKey);
            return self::error($exception->getMessage());
        }
    }

    public static function zAdd($key, $score, $value)
    {
        return Redis::zAdd($key, $score, $value);
    }

    public static function zPopMin($key)
    {
        return Redis::zPopMin($key);
    }

}
