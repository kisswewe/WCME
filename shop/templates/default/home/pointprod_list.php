<?php defined('In33hao') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_point.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<style type="text/css">
  .squares li:hover .goods-content{ height: 395px; }
</style>
<div class="ncp-container">
  <?php if ($_SESSION['is_login'] == '1'){ ?>
  <div class="ncp-member-top">
    <?php include_once BASE_TPL_PATH.'/home/pointshop.minfo.php'; ?>
  </div>
  <?php } ?>
  <div class="ncp-main-layout">
    <div class="ncp-category">
      <dl class="searchbox">
        <dt>排序方式：</dt>
        <dd><!-- 高级搜索start -->
          <ul>
            <input type="hidden" id="orderby" name="orderby" value="<?php echo $_GET['orderby']?$_GET['orderby']:'default';?>"/>
            
            <!-- 默认排序s -->
            <?php if (!$_GET['orderby'] || $_GET['orderby'] == 'default'){ ?>
            <li class="selected">默认排序</li>
            <?php } else { ?>
            <li nc_type="search_orderby" data-param='{"orderval":"default"}' style="cursor: pointer;">默认排序</li>
            <?php }?>
            <!-- 默认排序e --> 
            
            <!-- 积分值s -->
            <?php if ($_GET['orderby'] == 'pointsdesc'){//降序选中 ?>
            <li class="selected" nc_type="search_orderby" data-param='{"orderval":"pointsasc"}'>积分值<em class="desc"></em></li>
            <?php } elseif ($_GET['orderby'] == 'pointsasc') {//升序选中 ?>
            <li class="selected" nc_type="search_orderby" data-param='{"orderval":"pointsdesc"}'>积分值<em class="asc"></em></li>
            <?php } else {//未选中?>
            <li nc_type="search_orderby" data-param='{"orderval":"pointsdesc"}'>积分值<em class="desc"></em></li>
            <?php } ?>
            <!-- 积分值e --> 
            
            <!-- 上架时间s -->
            <?php if ($_GET['orderby'] == 'stimedesc'){//降序选中 ?>
            <li class="selected" nc_type="search_orderby" data-param='{"orderval":"stimeasc"}'>上架时间<em class="desc"></em></li>
            <?php } elseif ($_GET['orderby'] == 'stimeasc') {//升序选中 ?>
            <li class="selected" nc_type="search_orderby" data-param='{"orderval":"stimedesc"}'>上架时间<em class="asc"></em></li>
            <?php } else {//未选中?>
            <li nc_type="search_orderby" data-param='{"orderval":"stimedesc"}'>上架时间<em class="desc"></em></li>
            <?php } ?>
            <!-- 上架时间e --> 
            <li>&nbsp;</li>
            <!-- 会员等级s -->
            <!-- <li>会员等级：
              <select id="level" onchange="javascript:searchpointprod();">
                <option value='' selected >-请选择-</option>
                <?php if (!empty($output['membergrade_arr'])){ ?>
                <?php foreach ($output['membergrade_arr'] as $k=>$v){ ?>
                <option value="<?php echo $v['level'];?>" <?php echo (isset($_GET['level']) && ($_GET['level'] == $v['level']))?'selected':'';?>>V<?php echo $v['level'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </li> -->
            <!-- 会员等级e --> 
            <li>&nbsp;</li>
            <!-- 所需积分s -->
            <li>所需积分：
              <input type="text" id="points_min" class="text w50" value="<?php echo $_GET['points_min'];?>"/>
              ~
              <input type="text" id="points_max" class="text w50" value="<?php echo $_GET['points_max'];?>" />
              <a href="javascript:searchpointprod();" class="ncbtn">搜索</a> </li>
            <!-- 所需积分e -->
            <li>&nbsp;</li>
            <?php if($_SESSION['is_login'] == '1'){ ?>
            <li>
              <label for="isable"><input type="checkbox" id="isable" <?php echo intval($_GET['isable'])==1?'checked="checked"':'';?> onclick="javascript:searchpointprod();">
              &nbsp;只看我能兑换</label></li>
            <?php } ?>
          </ul>
          <!-- 高级搜索end --> </dd>
      </dl>
    </div>
    <!-- <?php echo "<pre>";print_r($output['pointprod_list']);?> -->
    <!-- 商品列表 -->
    <?php if (is_array($output['pointprod_list']) && count($output['pointprod_list'])){?>
    <div class="squares">
      <ul class="list_pic" style="width: 1200px">
        <?php foreach($output['pointprod_list'] as $value){?>
        <li class="item" style="width: 228px">
          <div class="goods-content" nctype_goods=" <?php echo $value['goods_commonid'];?>" nctype_store="<?php echo $value['store_id'];?>">
            <div class="goods-pic"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_commonid']));?>" target="_blank" title="<?php echo $value['goods_name'];?>"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo cthumb($value['goods_image'], 240,$value['store_id']);?>" rel="lazy" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /></a></div>
            <?php if (C('groupbuy_allow') && $value['goods_promotion_type'] == 1) {?>
            <div class="goods-promotion"><span>抢购商品</span></div>
            <?php } elseif (C('promotion_allow') && $value['goods_promotion_type'] == 2)  {?>
            <div class="goods-promotion"><span>限时折扣</span></div>
            <?php }?>
            <div class="goods-info">
              <div class="goods-pic-scroll-show">
                <ul>
                  <?php if(!empty($value['image'])) { array_splice($value['image'], 5);?>
                  <?php $i=0;foreach ($value['image'] as $val) {$i++?>
                  <li<?php if($i==1) {?> class="selected"<?php }?>><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo cthumb($val, 60,$value['store_id']);?>"/></a></li>
                  <?php }?>
                  <?php } else {?>
                  <li class="selected"><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo cthumb($value['goods_image'], 60,$value['store_id']);?>" /></a></li>
                  <?php }?>
                </ul>
              </div>
              <div class="goods-name"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_commonid']));?>" target="_blank" title="<?php echo $value['goods_jingle'];?>"><!-- <?php echo $value['goods_name_highlight'];?> --><em><?php echo $value['goods_name'];?></em></a></div>
              <div class="goods-price"> <em class="sale-price" title="<?php echo $lang['goods_class_index_store_goods_price'].$lang['nc_colon'].$lang['currency'].ncPriceFormat($value['goods_price']);?>"><?php echo ncPriceFormatForList($value['goods_price']);?></em> <em class="market-price" title="市场价：<?php echo $lang['currency'].$value['goods_marketprice'];?>"><?php echo ncPriceFormatForList($value['goods_marketprice']);?></em>
               <!--  <?php if($value["contractlist"]){?>
                <div class="goods-cti">
                  <?php foreach($value["contractlist"] as $gcitem_k=>$gcitem_v){?>
                  <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');" style="cursor: pointer;"<?php }?> title="<?php echo $gcitem_v['cti_name']; ?>">
                    <img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo $gcitem_v['cti_icon_url_60'];?>"/>
                  </span>
                  <?php }?>
                </div>
                <?php }?> -->
                 </div>
              <div class="goods-price" style="margin-bottom: 10px;"> <em class="sale-price" title="<?php echo $lang['goods_class_index_store_goods_price'].$lang['nc_colon'].$lang['currency'].ncPriceFormat($value['goods_promotion_price']);?>"><?php echo  round($value['integral'],0);?> 积分</em>
              </div>
              <div class="store"><a href="<?php echo urlShop('show_store','index',array('store_id'=>$value['store_id']), $value['store_domain']);?>" title="<?php echo $value['store_name'];?>" class="name"><?php echo $value['store_name'];?></a></div>
              <div class="add-cart">
                <a href="javascript:void(0);" nctype="add_cart" data-gid="<?php echo $value['goods_commonid'];?>"><i class="icon-shopping-cart"></i>加入购物车</a>
              </div>
            </div>
          </div>
        </li>
        <?php }?>
        <div class="clear"></div>
      </ul>
    </div>
    <div class="tc mt20 mb20">
      <div class="pagination"><?php echo $output['show_page'];?></div>
    </div>
    <?php }else{?>
    <div class="norecord"><?php echo $lang['pointprod_list_null'];?></div>
    <?php }?>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fly/jquery.fly.min.js"></script> 
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home.js" id="dialog_js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/search_goods.js" charset="utf-8"></script> 
<script>
$(function () {
	$("[nc_type='search_orderby']").click(function(){
		var data_str = $(this).attr('data-param');
	    eval( "data_str = "+data_str);
	    $("#orderby").val(data_str.orderval);
	    searchpointprod();
	});
});
function searchpointprod(){
	var url = 'index.php?act=pointprod&op=index';
	var orderby = $("#orderby").val();
	if(orderby){
		url += ('&orderby='+orderby);
	}
	var level = $("#level").val();
	if(level){
		url += ('&level='+level);
	}
	var points_min = $("#points_min").val();
	if(points_min){
		url += ('&points_min='+points_min);
	}
	var points_max = $("#points_max").val();
	if(points_max){
		url += ('&points_max='+points_max);
	}
	if($("#isable").attr("checked") == 'checked'){
		url += '&isable=1';
	}
	go(url);
}
</script>