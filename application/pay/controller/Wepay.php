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
     * 支付
     * @param Request $request
     * @return array|mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws PDOException
     */
    public function payOrder(Request $request)
    {
        if ($request->isPost()) {
            //获取订单号
            $order_id = $request->param('order_id');
            $remark = $request->param('remark');
            $address = $request->param('address');
            $order = new Order();

            return $order->Wepay($order_id, $remark, $address);
        }
        return config('PARAMS_ERROR');
    }


    /**
     * 异步回调地址
     * @throws Exception
     * @throws PDOException
     */
    public function notify()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: text/plain');
        $testxml = file_get_contents("php://input");
        $obj = json_encode(simplexml_load_string($testxml, 'SimpleXMLElement', LIBXML_NOCDATA));
        $obj=json_decode($obj, true);

        $order = new Order();
        $order->weUpdateOrder($obj);
    }
}