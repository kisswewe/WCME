<?php

/**
 * 购买流程
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class buyControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');
        if (!$_SESSION['member_id']) {
            redirect(urlLogin('login', 'index', array('ref_url' => request_uri())));
        }
        //验证该会员是否禁止购买
        if (!$_SESSION['is_buy']) {
            showMessage(Language::get('cart_buy_noallow'), '', 'html', 'error');
        }
        Tpl::output('hidden_rtoolbar_cart', 1);
    }

    /**
     * 实物商品 购物车、直接购买第一步:选择收获地址和配送方式
     */
    public function buy_step1Op() {
        //虚拟商品购买分流
        $this->_buy_branch($_POST);

        //得到购买数据
        $logic_buy = Logic('buy');
        $result = $logic_buy->buyStep1($_POST['cart_id'], $_POST['ifcart'], $_SESSION['member_id'], $_SESSION['store_id'], $_POST['jjg'], $this->member_info['orderdiscount'], $this->member_info['level'], $_POST['ifchain']);
        if (!$result['state']) {
            showMessage($result['msg'], '', 'html', 'error');
        } else {
            $result = $result['data'];
        }
        // 加价购
        Tpl::output('jjgValidSkus', $result['jjgValidSkus']);
        Tpl::output('jjgStoreCosts', $result['jjgStoreCosts']);

        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        Tpl::output('store_cart_list', $result['store_cart_list']);

        Tpl::output('store_goods_total', $result['store_goods_total']);
        Tpl::output('store_integral_total', $result['store_integral_total']);
        //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
        Tpl::output('store_premiums_list', $result['store_premiums_list']);
        Tpl::output('store_mansong_rule_list', $result['store_mansong_rule_list']);

        //返回店铺可用的代金券
        Tpl::output('store_voucher_list', $result['store_voucher_list']);

        //返回平台可用红包
        Tpl::output('rpt_list_json', json_encode($result['rpt_list']));

        //输出符合满X元包邮条件的店铺ID及包邮设置信息
        Tpl::output('cancel_calc_sid_list', $result['cancel_calc_sid_list']);

        //将商品ID、数量、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
        Tpl::output('freight_hash', $result['freight_list']);

        //输出用户默认收货地址
        if (!$_POST['ifchain']) {
            Tpl::output('address_info', $result['address_info']);
        }

        //输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
        Tpl::output('pay_goods_list', $result['pay_goods_list']);
        Tpl::output('ifshow_offpay', $result['ifshow_offpay']);
        Tpl::output('deny_edit_payment', $result['deny_edit_payment']);

        //输出是否有门店自提支付
        Tpl::output('ifshow_chainpay', $result['ifshow_chainpay']);
        Tpl::output('chain_store_id', $result['chain_store_id']);

        //不提供增值税发票时抛出true(模板使用)
        Tpl::output('vat_deny', $result['vat_deny']);

        //增值税发票哈希值(php验证使用)
        Tpl::output('vat_hash', $result['vat_hash']);

        //输出默认使用的发票信息
        Tpl::output('inv_info', $result['inv_info']);

        //删除购物车无效商品
        $logic_buy->delCart($_POST['ifcart'], $_SESSION['member_id'], $_POST['invalid_cart']);

        //标识购买流程执行步骤
        Tpl::output('buy_step', 'step2');

        Tpl::output('ifcart', $_POST['ifcart']);

        Tpl::output('ifchain', $_POST['ifchain']);

        //输出会员折扣
        Tpl::output('zk_list', $result['zk_list']);

        //店铺信息
        $store_list = Model('store')->getStoreMemberIDList(array_keys($result['store_cart_list']), 'store_id,member_id,store_domain,is_own_shop');
        Tpl::output('store_list', $store_list);

        //兑换积分比例
        $list_setting = Model('setting')->getListSetting();
        Tpl::output('list_setting', $list_setting['integral_rate']);
        //查询用户原有积分
        $member_points = Model('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
        Tpl::output('member_points', $member_points['member_points']);

        $current_goods_info = current($result['store_cart_list']);

        Tpl::output('current_goods_info', $current_goods_info[0]);

        Tpl::showpage('buy_step1');
    }

    //wap端所需要的参数
    public function find_parameterOp()
    {
        //兑换积分比例
        $list_setting = Model('setting')->getListSetting();
        $data['integral_rate'] = $list_setting['integral_rate'];
        //查询用户原有积分
        $member_points = Model('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
        $data['member_points'] = $member_points['member_points'];
        echo  json_encode($data);
    }
    //查询是否绑定福哈创特
    public function theo_accountOp()
    {
        $model = Model('member');
        $theo_account = $model->where(array('member_id'=>$_SESSION['member_id']))->find();
        if($theo_account['theo_account'] == null){
            $data['code'] = 400;
            $data['message'] = '没有绑定用户';
        }else{
            $url = C('theo_query_url');
            $input['account'] = $theo_account['theo_account'];
            $result =  $this->curl($url,$input);
            $result_decode = json_decode($result,true);
            if($result_decode['code'] == 200){
                $data['code'] = $result_decode['code'];
                $data['message'] = $result_decode['message'];
                $data['points'] = $result_decode['points'];
                $data['theo_account'] = $theo_account['theo_account'];
            }else{
                $data['code'] = $result_decode['code'];
                $data['message'] = $result_decode['message'];
            }
        }
        echo  json_encode($data);
    }
    //绑定福哈创特用户
    public function  binding_operationOp()
    {
        $url = C('theo_bound_url');
        $feature['member_name'] = $_POST['member_name'];
        $feature['member_passwd'] = $_POST['member_passwd'];
        $input['account'] = $_POST['account'];
        $input['password'] = $_POST['password'];
        $member = Model('member')->where(array('member_name'=>$feature['member_name'],'member_passwd'=>md5($feature['member_passwd'])))->find();
        if(empty($member)){
            $data['code'] = 400;
            $data['message'] = '用户名或者密码输入错误';
        }else{
            $result =  $this->curl($url,$input);
            $result_decode = json_decode($result,true);
            if($result_decode['code'] == 200){
                if(Model('member')->where(array('theo_account' =>$result_decode['data']['id']))->find()){
                    $data['code'] = 400;
                    $data['message'] = '该账号已经被绑定';
                }else{
                    if(Model('member')->where(array('member_id'=>$member['member_id']))->update(array('theo_account'=>$result_decode['data']['id']))){
                        $data['code'] = 200;
                        $data['message'] = '操作成功';
                        $data['points'] = $result_decode['data']['points'];
                        $data['theo_account'] = $result_decode['data']['id'];
                    }else{
                        $data['code'] = 400;
                        $data['message'] = '系统错误';
                    }
                }
            }else{
                $data['code'] = $result_decode['code'];
                $data['message'] = $result_decode['message'];
            }
        }
        echo json_encode($data);
    }

    //兑换积分
    public function int_transOp()
    {
        $url = C('theo_int_trans_url');
        $list_setting = Model('setting')->getListSetting();
        $input['theo_account'] = $_POST['theo_account'];
        $input['points'] = $_POST['points'];
        if($input['points'] > 0){
            $result = $this->curl($url,$input);
            $result_decode = json_decode($result,true);
            if($result_decode['code'] == 200){
                $member_points = $input['points'] * $list_setting['integral_rate'] / 100;
                if($member_points > 0){
                    if(Model('member')->where(array('member_id'=>$_SESSION['member_id']))->setInc('member_points',$member_points)){
                        $data['code'] = 200;
                        $data['message'] = '操作成功';
                    }else{
                        $data['code'] = 400;
                        $data['message'] = '系统错误';
                    }
                }else{
                    $data['code'] = '400';
                    $data['message'] = '参数错误';
                }
            }else{
                $data['code'] = $result_decode['code'];
                $data['message'] = $result_decode['message'];
            }
        }else{
            $data['code'] = 400;
            $data['message'] = '输入的兑换积分需要大于0';
        }
        echo json_encode($data);
    }
    function curl($url,$post_data){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $output = curl_exec($ch);
        curl_close($ch);

        //打印获得的数据
        return $output;
    }

    /**
     * 生成订单
     *
     */
    public function buy_step2Op() {
        $logic_buy = logic('buy');
        $result = $logic_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email'], $this->member_info['orderdiscount'], $this->member_info['level']);
        if (!$result['state']) {
            showMessage($result['msg'], 'index.php?act=cart', 'html', 'error');
        }

        //转向到商城支付页面
        redirect('index.php?act=buy&op=pay&pay_sn=' . $result['data']['pay_sn']);
    }

    /**
     * 下单时支付页面
     */
    public function payOp() {
        $pay_sn = $_GET['pay_sn'];

        $integral = Model('orders')->where(array('pay_sn' => $pay_sn))->field('buyer_id,integral_amount')->select();
        $result = Model('member')->where(array('member_id' => $integral[0]['buyer_id']))->field('member_points')->find();
        $integrals = 0;
        foreach($integral as $item){
            $integrals += $item['integral_amount'];
        }
        if ($result['member_points'] < $integrals) {
            showMessage('积分不足', 'index.php?act=member_order', 'html', 'error');
        }
        if (!preg_match('/^\d{18}$/', $pay_sn)) {
            showMessage(Language::get('cart_order_pay_not_exists'), 'index.php?act=member_order', 'html', 'error');
        }

        //查询支付单信息
        $model_order = Model('order');
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn' => $pay_sn, 'buyer_id' => $_SESSION['member_id']), true);
        if (empty($pay_info)) {
            showMessage(Language::get('cart_order_pay_not_exists'), 'index.php?act=member_order', 'html', 'error');
        }
        Tpl::output('pay_info', $pay_info);

        //取子订单列表
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = array('in', array(ORDER_STATE_NEW, ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition, '', '*', '', '', array(), true);
        if (empty($order_list)) {
            showMessage('未找到需要支付的订单', 'index.php?act=member_order', 'html', 'error');
        }

        //取特殊类订单信息
        $this->_getOrderExtendList($order_list);
        //处理预订单重复支付问题
        if ($order_list[0]['if_buyer_repay'] && $order_list[0]['pay_sn1'] == '') {
            $pay_sn_new = Logic('buy_1')->makePaySn($_SESSION['member_id']);
            $order_pay = array();
            $order_pay['pay_sn'] = $pay_sn_new;
            $order_pay['buyer_id'] = $_SESSION['member_id'];
            $order_pay_id = $model_order->addOrderPay($order_pay);
            if (!$order_pay_id) {
                showMessage('支付失败', 'index.php?act=member_order', 'html', 'error');
            }
            $update = $model_order->editOrder(array('pay_sn' => $pay_sn_new, 'pay_sn1' => $pay_sn), array('order_id' => $order_list[0]['order_id'], 'order_type' => 2));
            if (!$update) {
                showMessage('支付失败', 'index.php?act=member_order', 'html', 'error');
            } else {
                redirect('index.php?act=buy&op=pay&pay_sn=' . $pay_sn_new);
                exit;
            }
        }

        //定义输出数组
        $pay = array();
        //支付提示主信息
        $pay['order_remind'] = '';
        //重新计算支付金额
        $pay['pay_amount_online'] = 0;
        $pay['pay_amount_offline'] = 0;
        //订单总支付金额(不包含货到付款)
        $pay['pay_amount'] = 0;
        //充值卡支付金额(之前支付中止，余额被锁定)
        $pay['payd_rcb_amount'] = 0;
        //预存款支付金额(之前支付中止，余额被锁定)
        $pay['payd_pd_amount'] = 0;
        //还需在线支付金额(之前支付中止，余额被锁定)
        $pay['payd_diff_amount'] = 0;
        //账户可用金额
        $pay['member_pd'] = 0;
        $pay['member_rcb'] = 0;

        $logic_order = Logic('order');

        //计算相关支付金额
        foreach ($order_list as $key => $order_info) {
            if (!in_array($order_info['payment_code'], array('offline', 'chain'))) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay['pay_amount_online'] += $order_info['order_amount'];
                    $pay['payd_rcb_amount'] += $order_info['rcb_amount'];
                    $pay['payd_pd_amount'] += $order_info['pd_amount'];
                    $pay['payd_diff_amount'] += $order_info['order_amount'] - $order_info['rcb_amount'] - $order_info['pd_amount'];
                }
                $pay['pay_amount'] += $order_info['order_amount'];
            } else {
                $pay['pay_amount_offline'] += $order_info['order_amount'];
            }
            //显示支付方式
            if ($order_info['payment_code'] == 'offline') {
                $order_list[$key]['payment_type'] = '货到付款';
            } elseif ($order_info['payment_code'] == 'chain') {
                $order_list[$key]['payment_type'] = '门店支付';
            } else {
                $order_list[$key]['payment_type'] = '在线支付';
            }
        }
        if ($order_info['chain_id'] && $order_info['payment_code'] == 'chain') {
            $order_list[0]['order_remind'] = '下单成功，请在' . CHAIN_ORDER_PAYPUT_DAY . '日内前往门店提货，逾期订单将自动取消。';
            $flag_chain = 1;
        }

        Tpl::output('order_list', $order_list);

        //如果线上线下支付金额都为0，转到支付成功页
        if (empty($pay['pay_amount_online']) && empty($pay['pay_amount_offline'])) {
            redirect('index.php?act=buy&op=pay_ok&pay_sn=' . $pay_sn . '&is_chain=' . $flag_chain . '&pay_amount=' . ncPriceFormat($order_info['order_amount']));
        }

        //是否显示站内余额操作(如果以前没有使用站内余额支付过且非货到付款)
        $pay['if_show_pdrcb_select'] = ($pay['pay_amount_offline'] == 0 && $pay['payd_rcb_amount'] == 0 && $pay['payd_pd_amount'] == 0);

        //输出订单描述
        if (empty($pay['pay_amount_online'])) {
            $pay['order_remind'] = '下单成功，我们会尽快为您发货，请保持电话畅通。';
        } elseif (empty($pay['pay_amount_offline'])) {
            $pay['order_remind'] = '请您在' . (ORDER_AUTO_CANCEL_TIME * 60) . '分钟内完成支付，逾期订单将自动取消。 ';
        } else {
            $pay['order_remind'] = '部分商品需要在线支付，请您在' . (ORDER_AUTO_CANCEL_TIME * 60) . '分钟内完成支付，逾期订单将自动取消。';
        }
        if (!empty($order_list[0]['order_remind'])) {
            $pay['order_remind'] = $order_list[0]['order_remind'];
        }

        if ($pay['pay_amount_online'] > 0) {
            //显示支付接口列表
            $model_payment = Model('payment');
            $condition = array();
            $payment_list = $model_payment->getPaymentOpenList($condition);
            if (!empty($payment_list)) {
                unset($payment_list['predeposit']);
                unset($payment_list['offline']);
            }
            if (empty($payment_list)) {
                showMessage('暂未找到合适的支付方式', 'index.php?act=member_order', 'html', 'error');
            }
            Tpl::output('payment_list', $payment_list);
        }
        if ($pay['if_show_pdrcb_select']) {
            //显示预存款、支付密码、充值卡
            $available_predeposit = $available_rc_balance = 0;
            $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id']);
            if (floatval($buyer_info['available_predeposit']) > 0) {
                $pay['member_pd'] = $buyer_info['available_predeposit'];
            }
            if (floatval($buyer_info['available_rc_balance']) > 0) {
                $pay['member_rcb'] = $buyer_info['available_rc_balance'];
            }
            $pay['member_paypwd'] = $buyer_info['member_paypwd'] ? true : false;
        }

        Tpl::output('pay', $pay);

        //标识 购买流程执行第几步
        Tpl::output('buy_step', 'step3');
        Tpl::showpage('buy_step2');
    }

    /**
     * 特殊订单支付最后一步界面展示（目前只有预定）
     * @param unknown $order_list
     */
    private function _getOrderExtendList(& $order_list) {
        //预定订单
        if ($order_list[0]['order_type'] == 2) {
            $order_info = $order_list[0];
            $result = Logic('order_book')->getOrderBookInfo($order_info);
            if (!$result['data']['if_buyer_pay']) {
                showMessage('未找到需要支付的订单', 'index.php?act=member_order', 'html', 'error');
            }
            $order_list[0] = $result['data'];
            $order_list[0]['order_amount'] = $order_list[0]['pay_amount'];
            $order_list[0]['order_state'] = ORDER_STATE_NEW;
            if ($order_list[0]['if_buyer_repay']) {
                $order_list[0]['order_remind'] = '请您在 ' . date('Y-m-d H:i', $order_list[0]['book_list'][1]['book_end_time'] + 1) . ' 之前完成支付，否则订单会被自动取消。';
            }
        }
    }

    /**
     * 预存款充值下单时支付页面
     */
    public function pd_payOp() {
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/', $pay_sn)) {
            showMessage(Language::get('para_error'), urlMember('predeposit'), 'html', 'error');
        }

        //查询支付单信息
        $model_order = Model('predeposit');
        $pd_info = $model_order->getPdRechargeInfo(array('pdr_sn' => $pay_sn, 'pdr_member_id' => $_SESSION['member_id']));
        if (empty($pd_info)) {
            showMessage(Language::get('para_error'), '', 'html', 'error');
        }
        if (intval($pd_info['pdr_payment_state'])) {
            showMessage('您的订单已经支付，请勿重复支付', urlMember('predeposit'), 'html', 'error');
        }
        Tpl::output('pdr_info', $pd_info);

        //显示支付接口列表
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = array('not in', array('offline', 'predeposit', 'wxpay'));
        $condition['payment_state'] = 1;
        $payment_list = $model_payment->getPaymentList($condition);
        if (empty($payment_list)) {
            showMessage('暂未找到合适的支付方式', urlMember('predeposit'), 'html', 'error');
        }
        Tpl::output('payment_list', $payment_list);

        //标识 购买流程执行第几步
        Tpl::output('buy_step', 'step3');
        Tpl::showpage('predeposit_pay');
    }

    /**
     * 支付成功页面
     */
    public function pay_okOp() {
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/', $pay_sn)) {
            showMessage(Language::get('cart_order_pay_not_exists'), 'index.php?act=member_order', 'html', 'error');
        }

        //查询支付单信息
        $model_order = Model('order');
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn' => $pay_sn, 'buyer_id' => $_SESSION['member_id']));
        if (empty($pay_info)) {
            showMessage(Language::get('cart_order_pay_not_exists'), 'index.php?act=member_order', 'html', 'error');
        }
        Tpl::output('pay_info', $pay_info);

        Tpl::output('buy_step', 'step4');
        Tpl::showpage('buy_step3');
    }

    /**
     * 加载买家收货地址
     *
     */
    public function load_addrOp() {
        $model_addr = Model('address');
        //如果传入ID，先删除再查询
        if (!empty($_GET['id']) && intval($_GET['id']) > 0) {
            $model_addr->delAddress(array('address_id' => intval($_GET['id']), 'member_id' => $_SESSION['member_id']));
        }
        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];
        if (!C('delivery_isuse')) {
            $condition['dlyp_id'] = 0;
            $order = 'dlyp_id asc,address_id desc';
        }
        $list = $model_addr->getAddressList($condition, $order);
        Tpl::output('address_list', $list);
        Tpl::showpage('buy_address.load', 'null_layout');
    }

    /**
     * 载入门店自提点
     */
    public function load_chainOp() {
        $list = Model('chain')->getChainList(array('area_id' => intval($_GET['area_id']), 'store_id' => intval($_GET['store_id'])), 'chain_id,chain_name,area_info,chain_address');
        echo $_GET['callback'] . '(' . json_encode($list) . ')';
    }

    /**
     * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
     * 如果店铺统一设置了满免运费规则，则运费模板无效
     * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板，则两种商品数量相加后再应用该运费模板计算（即作为一种商品算运费）
     * 如果未找到运费模板，按免运费处理
     * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
     */
    public function change_addrOp() {
        $logic_buy = Logic('buy');
        if (empty($_POST['city_id'])) {
            $_POST['city_id'] = $_POST['area_id'];
        }

        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $_SESSION['member_id']);

        if (!empty($data)) {
            exit(json_encode($data));
        } else {
            exit('error');
        }
    }

    //根据门店自提站ID计算商品库存
    public function change_chainOp() {
        $logic_buy = Logic('buy');
        $data = $logic_buy->changeChain($_POST['chain_id'], $_POST['product']);
        if (!empty($data)) {
            exit(json_encode($data));
        } else {
            exit('error');
        }
    }

    /**
     * 添加新的收货地址
     *
     */
    public function add_addrOp() {
        $model_addr = Model('address');
        if (chksubmit()) {
            $count = $model_addr->getAddressCount(array('member_id' => $_SESSION['member_id']));
            if ($count >= 20) {
                exit(json_encode(array('state' => false, 'msg' => '最多允许添加20个有效地址')));
            }
            //验证表单信息
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["true_name"], "require" => "true", "message" => Language::get('cart_step1_input_receiver')),
                array("input" => $_POST["area_id"], "require" => "true", "validator" => "Number", "message" => Language::get('cart_step1_choose_area'))
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                $error = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($error) : $error;
                exit(json_encode(array('state' => false, 'msg' => $error)));
            }
            $data = array();
            $data['member_id'] = $_SESSION['member_id'];
            $data['true_name'] = $_POST['true_name'];
            $data['area_id'] = intval($_POST['area_id']);
            $data['city_id'] = intval($_POST['city_id']);
            $data['area_info'] = $_POST['region'];
            $data['address'] = $_POST['address'];
            $data['tel_phone'] = $_POST['tel_phone'];
            $data['mob_phone'] = $_POST['mob_phone'];
            // $data['tel_country_code'] = $_POST['tel_country_code'];
            // $data['phone_country_code'] = $_POST['phone_country_code'];
            $insert_id = $model_addr->addAddress($data);
            if ($insert_id) {
                exit(json_encode(array('state' => true, 'addr_id' => $insert_id)));
            } else {
                exit(json_encode(array('state' => false, 'msg' => '新地址添加失败')));
            }
        } else {
            $code = Model('country_code')->field('chinese_name,international_code')->limit(false)->select();
            Tpl::output('code', $code);
            Tpl::showpage('buy_address.add', 'null_layout');
        }
    }

    /**
     * 添加新的门店自提点
     *
     */
    public function add_chainOp() {
        Tpl::showpage('buy_address.add_chain', 'null_layout');
    }

    /**
     * 加载买家发票列表，最多显示10条
     *
     */
    public function load_invOp() {
        $logic_buy = Logic('buy');

        $condition = array();
        if ($logic_buy->buyDecrypt($_GET['vat_hash'], $_SESSION['member_id']) == 'allow_vat') {
            
        } else {
            Tpl::output('vat_deny', true);
            $condition['inv_state'] = 1;
        }
        $condition['member_id'] = $_SESSION['member_id'];

        $model_inv = Model('invoice');
        //如果传入ID，先删除再查询
        if (intval($_GET['del_id']) > 0) {
            $model_inv->delInv(array('inv_id' => intval($_GET['del_id']), 'member_id' => $_SESSION['member_id']));
        }
        $list = $model_inv->getInvList($condition, 10);
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                if ($value['inv_state'] == 1) {
                    $list[$key]['content'] = '普通发票' . ' ' . $value['inv_title'] . ' ' . $value['inv_content'];
                } else {
                    $list[$key]['content'] = '增值税发票' . ' ' . $value['inv_company'] . ' ' . $value['inv_code'] . ' ' . $value['inv_reg_addr'];
                }
            }
        }
        Tpl::output('inv_list', $list);
        Tpl::showpage('buy_invoice.load', 'null_layout');
    }

    /**
     * 新增发票信息
     *
     */
    public function add_invOp() {
        $model_inv = Model('invoice');
        if (chksubmit()) {
            //如果是增值税发票验证表单信息
            if ($_POST['invoice_type'] == 2) {
                if (empty($_POST['inv_company']) || empty($_POST['inv_code']) || empty($_POST['inv_reg_addr'])) {
                    exit(json_encode(array('state' => false, 'msg' => Language::get('nc_common_save_fail', 'UTF-8'))));
                }
            }
            $data = array();
            if ($_POST['invoice_type'] == 1) {
                $data['inv_state'] = 1;
                $data['inv_title'] = $_POST['inv_title_select'] == 'person' ? '个人' : $_POST['inv_title'];
                $data['inv_content'] = $_POST['inv_content'];
            } else {
                $data['inv_state'] = 2;
                $data['inv_company'] = $_POST['inv_company'];
                $data['inv_code'] = $_POST['inv_code'];
                $data['inv_reg_addr'] = $_POST['inv_reg_addr'];
                $data['inv_reg_phone'] = $_POST['inv_reg_phone'];
                $data['inv_reg_bname'] = $_POST['inv_reg_bname'];
                $data['inv_reg_baccount'] = $_POST['inv_reg_baccount'];
                $data['inv_rec_name'] = $_POST['inv_rec_name'];
                $data['inv_rec_mobphone'] = $_POST['inv_rec_mobphone'];
                $data['inv_rec_province'] = $_POST['vregion'];
                $data['inv_goto_addr'] = $_POST['inv_goto_addr'];
            }
            $data['member_id'] = $_SESSION['member_id'];
            //转码
            $data = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
            $insert_id = $model_inv->addInv($data);
            if ($insert_id) {
                exit(json_encode(array('state' => 'success', 'id' => $insert_id)));
            } else {
                exit(json_encode(array('state' => 'fail', 'msg' => Language::get('nc_common_save_fail', 'UTF-8'))));
            }
        } else {
            Tpl::showpage('buy_address.add', 'null_layout');
        }
    }

    /**
     * AJAX验证支付密码
     */
    public function check_pd_pwdOp() {
        if (empty($_GET['password']))
            exit('0');
        $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id'], 'member_paypwd');
        echo ($buyer_info['member_paypwd'] != '' && $buyer_info['member_paypwd'] === md5($_GET['password'])) ? '1' : '0';
    }

    /**
     * F码验证
     */
    public function check_fcodeOp() {
        $result = logic('buy')->checkFcode($_GET['goods_id'], $_GET['fcode']);
        echo $result['state'] ? '1' : '0';
        exit;
    }

    /**
     * 得到所购买的id和数量
     *
     */
    private function _parseItems($cart_id) {
        //存放所购商品ID和数量组成的键值对
        $buy_items = array();
        if (is_array($cart_id)) {
            foreach ($cart_id as $value) {
                if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
                    $buy_items[$match[1][0]] = $match[2][0];
                }
            }
        }

        return $buy_items;
    }

    /**
     * 购买分流
     */
    private function _buy_branch($post) {

        if (!$post['ifcart']) {
            //取得购买商品信息
            $buy_items = $this->_parseItems($post['cart_id']);
            $goods_id = key($buy_items);
            $quantity = current($buy_items);

            $goods_info = Model('goods')->getGoodsOnlineInfoAndPromotionById($goods_id);
            if ($goods_info['is_virtual']) {
                redirect('index.php?act=buy_virtual&op=buy_step1&goods_id=' . $goods_id . '&quantity=' . $quantity);
            }
        }
    }

}
