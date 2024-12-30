<?php


namespace lz\admin\Controllers;



use lz\admin\Services\RedisService;
use lz\admin\Services\CacheKeyService;
use Illuminate\Http\Request;

class SysTableController extends Controller
{

    public function indexView()
    {
        $key = CacheKeyService::SYS_TABLE;
        $data = RedisService::get($key);
        return $this->view('/lzadmin/table/index', compact('data'));
    }

    public function infoView(Request $request)
    {
        $table = $request->input('table');
        $table = json_decode($table);
        $key = CacheKeyService::SYS_TABLE_FIELD;
        $data = [];
        foreach ($table as $item) {
            $data[] = [
                'table_name' => $item,
                'fields' => RedisService::hget($key, $item)
            ];
        }
        return $this->view('/lzadmin/table/info', compact('data'));
    }
}
