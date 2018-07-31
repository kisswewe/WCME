<?php

/**
 * 系统操作日志
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');

class country_codeControl extends SystemControl {

    const EXPORT_SIZE = 1000;

    public function __construct() {
        parent::__construct();
        Language::read('admin_log');
    }

    public function indexOp() {
        $this->listOp();
    }

    /**
     * 日志列表
     *
     */
    public function listOp() {
        Tpl::setDirquna('system');
        Tpl::showpage('country_code.index');
    }

    public function get_xmlOp() {
        $model = Model('country_code');
        $condition = array();
        if ($_GET['chinese_name'] != '') {
            $condition['chinese_name'] = $_GET['chinese_name'];
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $list = $model->where($condition)->order($order)->page($_POST['rp'])->select();
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($list as $k => $info) {
            $list = array();
         //   $list['operation'] = "<a class=\"btn blue\" href=\"index.php?act=country_code&op=add&id=" . $info['id'] . "\"><i class=\"fa fa-pencil-square-o\"></i>编辑</a>
	//		<a onclick=\"fg_delete(" . $info['id'] . ")\" class=\"btn red\"><i class=\"fa fa-trash-o\"></i>删除</a>";
            $list['international_name'] = $info['international_name'];
            $list['chinese_name'] = $info['chinese_name'];
            $list['international_code'] = $info['international_code'];
            $list['international_brief'] = $info['international_brief'];
            $list['continent'] = $info['continent'];
            $data['list'][$info['id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    public function deleteOp() {
        Model('country_code')->where(array('id' => $_GET['id']))->delete();
    }

    public function addOp() {
        $data['international_name'] = $_POST['international_name'];
        $data['chinese_name'] = $_POST['chinese_name'];
        $data['international_code'] = $_POST['international_code'];
        $data['international_brief'] = $_POST['international_brief'];
        $data['continent'] = $_POST['continent'];
        if ($_POST) {
            if ($_POST['id']) {
                $result = Model('country_code')->where(array('id' => $_POST['id']))->update($data);
            } else {
                $result = Model('country_code')->insert($data);
            }
            if ($result) {
                showMessage('操作成功', 'index.php?act=country_code');
            }
        }
        $code = Model('country_code')->where(array('id' => $_GET['id']))->find();
        Tpl::output('code', $code);
        Tpl::setDirquna('system');
        Tpl::showpage('country_code.add');
    }

    public function excelOp(){
            require_once $_SERVER['DOCUMENT_ROOT'] . '/data/phpexcel/Classes/PHPExcel.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/data/phpexcel/Classes/PHPExcel/IOFactory.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/data/phpexcel/Classes/PHPExcel/Reader/Excel5.php';
            $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format                       
            $objPHPExcel = $objReader->load($_FILES['area_list']['tmp_name']); //$filename可以是上传的文件，或者是指定的文件
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
           //  print_r($highestRow);exit;           
            $k = 0;
            for ($j = 2; $j <= $highestRow; $j++) {
                $data['international_name'] = $objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue();
                $data['chinese_name'] = $objPHPExcel->getActiveSheet()->getCell("B" . $j)->getValue();
                $data['international_brief'] = $objPHPExcel->getActiveSheet()->getCell("C" . $j)->getValue();
                $data['international_code'] = $objPHPExcel->getActiveSheet()->getCell("D" . $j)->getValue();
                $data['continent'] = $objPHPExcel->getActiveSheet()->getCell("E" . $j)->getValue();
                $data['price'] = $objPHPExcel->getActiveSheet()->getCell("F" . $j)->getValue();
                $data['create_time'] = time();
                $model = Model("country_code");
                $model->insert($data);
            }
             showMessage('操作成功', 'index.php?act=country_code');
    }
}
