<?php


namespace lz\admin\Services;


use lz\admin\Models\MenuModel;
use Illuminate\Support\Facades\DB;
use lz\admin\Traits\ResTrait;

class MenuService
{

    use ResTrait;

    const SYS_MENU = [
        [
            'title' => '开发者管理',
            'icon' => 'layui-icon layui-icon-set',
            'route' => null,
            'is_hide' => 0,
            'children' => [
                [
                    'title' => '菜单权限配置',
                    'route' => '/sys/menu',
                    'icon' => '',
                    'is_hide' => 0,
                    'children' => null
                ],
                [
                    'title' => '模型设置',
                    'route' => '/sys/model',
                    'icon' => '',
                    'is_hide' => 0,
                    'children' => null
                ],
                [
                    'title' => '选项设置',
                    'route' => '/sys/option',
                    'icon' => '',
                    'is_hide' => 0,
                    'children' => null
                ],
                [
                    'title' => '系统配置',
                    'route' => '/sys/config',
                    'icon' => '',
                    'is_hide' => 0,
                    'children' => null
                ],
                [
                    'title' => '刷新系统缓存',
                    'route' => '/sys/refreshCache',
                    'icon' => '',
                    'is_hide' => 0,
                    'children' => null
                ],
                [
                    'title' => '请求日志',
                    'route' => '/sys/log',
                    'icon' => '',
                    'is_hide' => 0,
                    'children' => null
                ],
                [
                    'title' => '系统管理员',
                    'route' => null,
                    'icon' => 'layui-icon layui-icon-group',
                    'is_hide' => 0,
                    'children' => [
                        [
                            'title' => '用户',
                            'route' => '/sys/user',
                            'icon' => '',
                            'is_hide' => 0,
                            'children' => null
                        ],
                        [
                            'title' => '角色',
                            'route' => '/sys/role',
                            'icon' => '',
                            'is_hide' => 0,
                            'children' => null
                        ],
                    ]
                ],
            ]
        ]
    ];

    /**
     * 刷新缓存
     * @param int $id
     * @return array
     */
    public static function refreshCache($id = 0)
    {
        $query = MenuModel::query();
        $query->select([
            'id',
            'parent_id',
            'title',
            'icon',
            'route',
            'is_hide',
            'sort'
        ]);
        if (!empty($id)) {
            $query->where('id', $id);
            $data = $query->first()->toArray();
            RedisService::hset(CacheKeyService::SYS_MENU, $id, $data);
        } else {
            $query->orderBy('id', 'ASC');
            $data = $query->get()->toArray();
            RedisService::del(CacheKeyService::SYS_MENU);
            foreach ($data as $item) {
                RedisService::hset(CacheKeyService::SYS_MENU, $item['id'], $item);
            }
        }
        //todo 更新可访问菜单缓存
        $access_menus = DB::table('sys_menu')
            ->where('is_hide', 0)
            ->whereNotNull('route')
            ->select([
                'route',
                'title'
            ])->get();
        RedisService::set(CacheKeyService::SYS_ACCESS_MENU, $access_menus);
        return $data;
    }

    /**
     * 清除缓存
     * @param $id
     * @return bool
     */
    public static function deleteCache($id)
    {
        //清除菜单缓存
        RedisService::hdel(CacheKeyService::SYS_MENU, $id);
        //清除菜单权限缓存
        FunctionService::deleteCache($id);
        return true;
    }

    /**
     * 获取所有菜单
     * @return array
     */
    public static function getAll()
    {
        $data = RedisService::hgetAll(CacheKeyService::SYS_MENU);
        if (empty($data)) {
            $data = self::refreshCache();
        } else {
            $data = array_values($data);
        }
        $sort = array_column($data, 'sort');
        $id = array_column($data, 'id');
        array_multisort($sort, SORT_ASC, $id, SORT_ASC, $data);
        return $data;
    }

    /**
     * 获取树形菜单结构
     * @return array
     */
    public static function getLoginMenu()
    {

        $data = self::getAll();
        if (UserService::isSuperUser()) {
            $menus = customArrFormatTree($data, 0);
            $dev_menus = self::SYS_MENU;
            $menus = array_merge($dev_menus, $menus);
        } else {
            $menu_ids = UserService::getLoginUserMenuIds();
            foreach ($data as &$datum) {
                if (!in_array($datum['id'], $menu_ids)) {
                    $datum['is_hide'] = 1;
                }
            }
            $menus = customArrFormatTree($data, 0);
        }
        return $menus;
    }

    /**
     * 获取菜单权限树形结构
     * @param $function_id
     * @return array
     */
    public static function getTreeDataHasFunction($function_id)
    {
        $data = self::getAll();
        $data = customArrFormatTree($data, 0);
        $menu = [];
        foreach ($data as $item1) {
            //todo 第一层菜单
            $temp = [
                'title' => $item1['title'],
                'spread' => false,
                'children' => []
            ];
            if (empty($item1['children'])) {
                self::menuFunctionFormat($temp, $item1['id'], $function_id);
            } else {
                //todo 第二层菜单
                foreach ($item1['children'] as $item2) {
                    $temp2 = [
                        'title' => $item2['title'],
                        'spread' => false,
                        'children' => []
                    ];
                    if (empty($item2['children'])) {
                        self::menuFunctionFormat($temp2, $item2['id'], $function_id);
                    } else {
                        //todo 第三层菜单
                        foreach ($item2['children'] as $item3) {
                            $temp3 = [
                                'title' => $item3['title'],
                                'spread' => false,
                                'children' => []
                            ];
                            self::menuFunctionFormat($temp3, $item3['id'], $function_id);
                            $temp2['children'][] = $temp3;
                        }
                    }
                    $temp['children'][] = $temp2;
                }
            }
            $menu[] = $temp;
        }
        return $menu;
    }

    private static function menuFunctionFormat(&$temp, $menu_id, $function_id)
    {
        $functions = FunctionService::getFunctionsByMenuId($menu_id);
        ksort($functions);
        foreach ($functions as $function) {
            $temp['children'][] = [
                'field' => 'function_id[]',
                'id' => $function['id'],
                'title' => $function['title'],
                'checked' => in_array($function['id'], $function_id),
            ];
        }
    }
}
