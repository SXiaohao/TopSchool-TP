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
            $buyer_id = Db::table('ym_order')
                ->where($order_id)
                ->value('buyer_id');
            $address = Db::table('ym_user_address')
                ->where('user_id', $buyer_id)
                ->value('address');
            return ['status' => 200, 'msg' => '查询成功！！',
                'itemList' => $itemList, 'address' => $address];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400, 'msg' => '查询失败！！'];
    }
}