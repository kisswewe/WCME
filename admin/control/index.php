<?php
/**
 * 默认展示页面
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class indexControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('index');
    }
    public function indexOp(){
        //输出管理员信息
        Tpl::output('admin_info',$this->getAdminInfo());
        //输出菜单
        $result = $this->getNav();
        list($top_nav, $left_nav, $map_nav, $quicklink) = $result;
        Tpl::output('top_nav',$top_nav);
        Tpl::output('left_nav',$left_nav);
        Tpl::output('map_nav',$map_nav);
        // 快捷菜单
        Tpl::output('quicklink', $quicklink);

        Tpl::showpage('index','index_layout');
    }

    /**
     * 退出
     */
    public function logoutOp(){
        setNcCookie('sys_key','',-1,'',null);
        @header("Location: index.php");
        exit;
    }
    /**
     * 修改密码
     */
    public function modifypwOp(){
        if (chksubmit()){
            if (trim($_POST['new_pw']) !== trim($_POST['new_pw2'])){
                //showMessage('两次输入的密码不一致，请重新输入');
                showMessage(Language::get('index_modifypw_repeat_error'));
            }
            $admininfo = $this->getAdminInfo();
            //查询管理员信息
            $admin_model = Model('admin');
            $admininfo = $admin_model->getOneAdmin($admininfo['id']);
            if (!is_array($admininfo) || count($admininfo)<= 0){
                showMessage(Language::get('index_modifypw_admin_error'));
            }
            //旧密码是否正确
            if ($admininfo['admin_password'] != md5(trim($_POST['old_pw']))){
                showMessage(Language::get('index_modifypw_oldpw_error'));
            }
            $new_pw = md5(trim($_POST['new_pw']));
            $update = array();
            $update['admin_password'] = $new_pw;
            $update['admin_id'] = $admininfo['admin_id'];
            $result = $admin_model->updateAdmin($update);
            if ($result){
                showDialog(Language::get('index_modifypw_success'), urlAdmin('index', 'logout'), 'succ');
            }else{
                showMessage(Language::get('index_modifypw_fail'));
                showDialog(Language::get('index_modifypw_fail'), '', '', 'CUR_DIALOG.click();');
            }
        }else{
            Language::read('admin');
            Tpl::showpage('admin.modifypw', 'null_layout');
        }
    }
    
    public function save_avatarOp() {
        $admininfo = $this->getAdminInfo();
        $admin_model = Model('admin');
        $admininfo = $admin_model->getOneAdmin($admininfo['id']);
        if ($_GET['avatar'] == '') {
            echo false;die;
        }
        @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_ADMIN_AVATAR . '/' . cookie('admin_avatar'));
        $update['admin_avatar'] = $_GET['avatar'];
        $update['admin_id'] = $admininfo['admin_id'];
        $result = $admin_model->updateAdmin($update);
        if ($result) {
            setNcCookie('admin_avatar',$_GET['avatar'],86400 * 365,'',null);
        }
        echo $result;die;
    }

    public function addAddreOp(){
        header('Content-type:text/html;charset=utf-8');
        $file=BASE_UPLOAD_PATH."/LocList.xml";
        $xml = simplexml_load_file($file);
        $xmljson= json_encode($xml);
        $xml=json_decode($xmljson,true);

        $data  = array();
        
        $xml = $xml['CountryRegion'];
        //筛选地区
        $filtrate_area = array('美国','加拿大','澳大利亚','新西兰','英国','法国','德国','捷克','荷兰','瑞士','希腊','挪威','瑞典','丹麦','芬兰','爱尔兰','奥地利','意大利','乌克兰','俄罗斯','西班牙','韩国','新加坡','马来西亚','印度','泰国','日本','巴西','阿根廷','南非','埃及');
        $i = 534;
        $k = 5025;
        // echo "<pre>";print_r($xml);exit();
        foreach ($xml as $key => $value) {
            if(in_array($value['@attributes']['Name'], $filtrate_area)){
                $data[] = array(
                    'area_id'           =>  ++$i,
                    'area_name'         =>  $value['@attributes']['Name'],
                    'area_parent_id'    =>  35,
                    'area_region'       =>  '海外',
                    'area_deep'         =>  2
                );
                if(isset($value['State'])){
                    $arr = isset($value['State']['City'])?$value['State']['City']:$value['State'];
                    echo "<pre>";print_r($arr);exit;
                    foreach ($arr as $key1 => $value1) {
                        $data[] = array(
                            'area_id'           =>  $k++,
                            'area_name'         =>  $value1['@attributes']['Name'],
                            'area_parent_id'    =>  $i,
                            'area_region'       =>  '',
                            'area_deep'         =>  3
                        );
                    }
                }
            }
        }
        // echo "<pre>";print_r($data);exit();
        $result = Db::insertAll('area',$data);
        if($result){
            echo 'ok';
        }else{
            echo 'no';
        }
        // $data = array(
        //     'area_parent_id' => 35,
        //     'area_region' => '海外'
        //     );
        // $where = 'area_parent_id = 45055';
        // $result = Db::update('area',$data,$where);
        // if($result){
        //     echo 'ok';
        // }else{
        //     echo 'no';
        // }
    }
}
