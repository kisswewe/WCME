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
class currency_rateControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->currency_rateOp();
    }

    /**
     * 分销比例设置
     */
    public function currency_rateOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["MYR_rate"],"require"=>"true", 'validator'=>'Integral_rate', "message"=>'兑换率请填写数字'),
                array("input"=>$_POST["TWD_rate"],"require"=>"true", 'validator'=>'Integral_rate', "message"=>'兑换率请填写数字'),
                array("input"=>$_POST["usd_rate"],"require"=>"true", 'validator'=>'Integral_rate', "message"=>'兑换率请填写数字'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error);
            }else {
                $update_array = array();
                $update_array['MYR_rate'] = round($_POST['MYR_rate'],2);
                $update_array['TWD_rate'] = round($_POST['TWD_rate'],2);
                $update_array['usd_rate'] = round($_POST['usd_rate'],2);
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
        Tpl::showpage('operating.setting.currency_rate');
    }
}
