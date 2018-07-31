<?php
/**
 *
 * 运营
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class distribution_rateControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->distribution_rateOp();
    }

    /**
     * 分销比例设置
     */
    public function distribution_rateOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["first_referrer_rate"],"require"=>"true", 'validator'=>'Integral_rate', "message"=>'第一代推荐人比例请填写数字'),
                array("input"=>$_POST["second_referrer_rate"],"require"=>"true", 'validator'=>'Integral_rate', "message"=>'第二代推荐人比例请填写数字'),
                array("input"=>$_POST["recommend_goods_rate"],"require"=>"true", 'validator'=>'Integral_rate', "message"=>'商品推荐人比例请填写数字'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error);
            }else {
                $update_array = array();
                $update_array['first_referrer_rate'] = round($_POST['first_referrer_rate'],2);
                $update_array['second_referrer_rate'] = round($_POST['second_referrer_rate'],2);
                $update_array['recommend_goods_rate'] = round($_POST['recommend_goods_rate'],2);
                $result = $model_setting->updateSetting($update_array);
                if ($result === true){
                    showDialog(L('nc_common_save_succ'));
                }else {
                    showDialog(L('nc_common_save_fail'));
                }
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
		Tpl::setDirquna('shop');
        Tpl::showpage('operating.setting.distribution_rate');
    }
}
