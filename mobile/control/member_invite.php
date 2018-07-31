<?php
/**
 * 我的余额 
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');


class member_inviteControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 添加返利
     */
    public function indexOp() {
		$member_id=$this->member_info['member_id'];

		
		$encode_member_id = base64_encode(intval($member_id)*1);
	    $myurl=BASE_SITE_URL."/#V5".$encode_member_id;
		
		$str_member="memberqr_".$member_id;
		$myurl_src=UPLOAD_SITE_URL.DS."shop".DS."member".DS.$str_member.'.png';
		$imgfile=BASE_UPLOAD_PATH.DS."shop".DS."member".DS.$str_member . '.png';
		if(!file_exists($imgfile)){			
			require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
			$PhpQRCode = new PhpQRCode();
			
			$PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS."shop".DS."member".DS);
			$PhpQRCode->set('date',$myurl);
			$PhpQRCode->set('pngTempName', $str_member . '.png');
			$PhpQRCode->init();
		}
		$member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['point'] = $this->member_info['member_points'];
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
		$member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
        $member_info['myurl']=$myurl;
		$member_info['myurl_src']=$myurl_src;
		//下载连接
		$mydownurl=BASE_SITE_URL."/index.php?act=invite&op=downqrfile&id=".$member_id;
		$member_info['mydownurl']=$mydownurl;
        output_data(array('member_info' => $member_info));
    }
	/**

     * 获取一级会员佣金列表

     */

    public function inviteoneOp() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_one'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);     

		

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = $member_model->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总金额

		    $invite_amount = $member_model->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }

   /**

     * 获取二级会员佣金列表

     */

    public function invitetwoOp() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_two'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = $member_model->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总金额

		    $invite_amount = $member_model->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }

	

  /**

     * 获取三级会员佣金列表

     */

    public function invitethirOp() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_three'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = $member_model->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总金额

		    $invite_amount = $member_model->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }
}
