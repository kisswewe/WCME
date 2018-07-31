<?php

/**
 * 收货地址
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class member_codeControl extends BaseMemberControl {


    public function codeOp() {
       // self::profile_menu('code', 'code');
       // Tpl::output('address_list', $address_list);
        Tpl::showpage('member_code.index');
    }


}
