<?php


namespace app\pay\controller;


use app\pay\model\Order;
use Notify_pub;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Request;
use wxpay\DownloadBill;
use think\Controller;
use wxpay\JsapiPay;
use wxpay\Notify;
use wxpay\Query;
use wxpay\Refund;
use wxpay\RefundQuery;
use wxpay\WapPay;
use WxPayException;

class Wepay extends Controller
{


    /**
     *  H5支付
     * @param Request $request
     * @return array|mixed
     * @throws WxPayException
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws PDOException
     */
    public function payOrder(Request $request)
    {
        if ($request->isPost()) {
            //获取订单号
            $order_id = $request->param('order_id');
            $remark = $request->param('remark');
            $address=$request->param('address');
            $order = new Order();

            return $order->Wepay($order_id, $remark,$address);
        }
        return config('PARAMS_ERROR');
    }

    // 通知测试
    public function notify()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: text/plain');


        echo 'SUCCESS';
    }
}