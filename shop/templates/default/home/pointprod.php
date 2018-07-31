<?php defined('In33hao') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_point.css" rel="stylesheet" type="text/css">
<div class="ncp-container">
  <div class="ncp-base-layout">
    <div class="ncp-member-left">
      <?php if($_SESSION['is_login'] == '1'){?>
      <?php include_once BASE_TPL_PATH.'/home/pointshop.minfo.php'; ?>
      <?php } else { ?>
      <div class="ncp-not-login">
        <div class="member"><a href="javascript:login_dialog();">立即登录</a>
          <p>获知会员信息详情</p>
        </div>
        <div class="function" style="border: none;">
        <i class="voucher"></i>
          <dl>
            <dt>店铺代金券</dt>
            <dd>换取店铺代金券购买商品更划算</dd>
          </dl>
        </div>
        <div class="function">
        <i class="exchange"></i>
          <dl>
            <dt>积分兑换礼品</dt>
            <dd>可使用积分兑换商城超值礼品</dd>
          </dl>
        </div>
        <div class="button"> <a href="javascript:login_dialog();" class="ncbtn" style="width:120px;"><?php echo $lang['pointprod_list_hello_login']; ?></a> </div>
      </div>
      <?php }?>
    </div>
    <div class="ncp-banner-right"><?php echo loadadv(35,'html');?></div>
  </div>
  <?php if (C('pointprod_isuse')==1){?>
  <div class="ncp-main-layout mb30">
    <div class="title">
      <h3><i class="exchange"></i>积分商品</h3>
      <span class="more"><a href="<?php echo urlShop('pointprod', 'plist');?>"><?php echo $lang['pointprod_list_more'];?></a></span> </div>
    <?php if (is_array($output['recommend_pointsprod']) && count($output['recommend_pointsprod'])>0){?>
    <ul class="ncp-exchange-list">
      <?php foreach ($output['recommend_pointsprod'] as $k=>$v){?>
      <li>
        <div class="gift-pic"><a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_commonid']));?>"> <img src="<?php echo $v['goods_image'] ?>" alt="<?php echo $v['goods_name']; ?>" /> </a></div>
        <div class="gift-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_commonid']));?>" target="_blank" tile="<?php echo $v['goods_name']; ?>"><?php echo $v['goods_name']; ?></a></div>
        <div class="goods-price" style="color: red;font-weight: 800"><strong class="sale-price">¥<?php echo $v['goods_price']; ?>+<?php echo $v['integral']; ?></strong></a></div>
      </li>
      <?php } ?>
    </ul>
    <?php }else{?>
    <div class="norecord"><?php echo $lang['pointprod_list_null'];?></div>
    <?php }?>
  </div>
  <?php }?>
</div>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home.js" id="dialog_js" charset="utf-8"></script>
