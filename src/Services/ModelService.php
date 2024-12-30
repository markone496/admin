<?php


namespace lz\admin\Services;


use lz\admin\Models\ModelModel;
use lz\admin\Traits\ResTrait;

class ModelService
{

    use ResTrait;

    /**
     * 刷新缓存
     * @param $id
     * @return bool
     */
    public static function refreshCache($id = null)
    {
        $key = CacheKeyService::SYS_MODEL;
        $query = ModelModel::query();
        $query->select([
            'id',
            'title',
            'choose_type',
            'table_config',
            'cols_config',
            'search_config',
            'form_config',
            'toolbar_config',
            'tool_config',
            'info_config',
        ]);
        if (!empty($id)) {
            $query->where('id', $id);
            $data = $query->first()->toArray();
            RedisService::hset($key, $id, $data);
        } else {
            RedisService::del($key);
            $data = $query->get()->toArray();
            foreach ($data as $datum) {
                RedisService::hset($key, $datum['id'], $datum);
            }
        }
        return true;
    }

    /**
     * 删除缓存
     * @param $id
     * @return bool
     */
    public static function deleteCache($id)
    {
        $key = CacheKeyService::SYS_MODEL;
        RedisService::hdel($key, $id);
        return true;
    }

    /**
     * 获取缓存
     * @param $id
     * @return mixed
     */
    public static function getModelById($id)
    {
        $key = CacheKeyService::SYS_MODEL;
        return RedisService::hget($key, $id);
    }

    /**
     * 获取模型表单
     * @param $id
     * @param array $data
     * @return array
     */
    public static function getModelForm($id, $data = [])
    {
        $model = self::getModelById($id);
        if (empty($model)) {
            return [];
        }
        $form = [];
        foreach ($model['form_config'] as $item) {
            if ($item['ban_edit'] == 1) {
                continue;
            }
            $value = isset($data[$item['field']]) ? $data[$item['field']] : null;
            $option = [];
            if (!empty($item['option'])) {
                $option = StaticDataService::getData('model-form-option-' . $item['option'], function () use ($item) {
                    return OptionService::getOptionById($item['option']);
                });
            }
            if (!empty($item['table'])) {
                $field = $item['table'] . "[{$item['field']}]";
            } else {
                $field = $item['field'];
            }
            $form[] = FormService::formRender($item['category'], $item['title'], $field, true, $value, $item['required'], $item['type'], false, $option, $item['custom_class'], $item['ban_edit']);
        }
        return $form;
    }


}
