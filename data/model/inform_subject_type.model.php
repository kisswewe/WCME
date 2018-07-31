<?php
/**
 * 举报类型模型
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class inform_subject_typeModel extends Model {

    /*
     * 构造条件
     */
    private function getCondition($condition){
        $condition_str = '' ;
        if(!empty($condition['inform_type_state'])) {
            $condition_str.= "and  inform_type_state = '{$condition['inform_type_state']}'";
        }
        if(!empty($condition['in_inform_type_id'])) {
            $condition_str .= " and inform_type_id in (".$condition['in_inform_type_id'].')';
        }
        return $condition_str;
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function saveInformSubjectType($param){

        return Db::insert('inform_subject_type',$param) ;

    }

    /*
     * 更新
     * @param array $update_array
     * @param array $where_array
     * @return bool
     */
    public function updateInformSubjectType($update_array, $where_array){

        $where = $this->getCondition($where_array) ;
        return Db::update('inform_subject_type',$update_array,$where) ;

    }

    /*
     * 删除
     * @param array $param
     * @return bool
     */
    public function dropInformSubjectType($param){

        $where = $this->getCondition($param) ;
        return Db::delete('inform_subject_type', $where) ;

    }

    /*
     *  获得举报类型列表
     *  @param array $condition
     *  @param obj $page    //分页对象
     *  @return array
     */
    public function getInformSubjectType($condition='',$page=''){

        $param = array() ;
        $param['table'] = 'inform_subject_type' ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' inform_type_id desc ';
        return Db::select($param,$page) ;

    }

    /*
     *  获得有效举报类型列表
     *  @param array $condition
     *  @param obj $page    //分页对象
     *  @return array
     */
    public function getActiveInformSubjectType($page='') {

        //搜索条件
        $condition = array();
        $condition['order'] = 'inform_type_id asc';
        $condition['inform_type_state'] = 1;
        return $this->getInformSubjectType($condition,$page) ;

    }


}
