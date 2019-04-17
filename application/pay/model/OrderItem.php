<?php


namespace app\pay\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class OrderItem extends Model
{
    public function getItemList($order_id)
    {
        try {
            $itemList = Db::table('ym_order_item')
                ->where(['order_id' => $order_id])
                ->select();

            return ['status' => 200, 'msg' => '查询成功！！',
                'itemList' => $itemList];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400, 'msg' => '查询失败！！'];
    }
}