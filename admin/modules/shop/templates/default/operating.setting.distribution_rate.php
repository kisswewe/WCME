<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>分销比例设置</h3>
        <h5></h5>
      </div>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="first_referrer_rate">第一推荐人比例</label>
        </dt>
        <dd class="opt">
          <input type="text" id="first_referrer_rate" name="first_referrer_rate" value="<?php echo $output['list_setting']['first_referrer_rate'];?>" class="input-txt">
          <span class="err">%</span>
          <p class="notic">保留两位有效数字。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="second_referrer_rate">第二推荐人比例</label>
        </dt>
        <dd class="opt">
          <input type="text" id="second_referrer_rate" name="second_referrer_rate" value="<?php echo $output['list_setting']['second_referrer_rate'];?>" class="input-txt">
          <span class="err">%</span>
          <p class="notic">保留两位有效数字。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="recommend_goods_rate">商品推荐人比例</label>
        </dt>
        <dd class="opt">
          <input type="text" id="recommend_goods_rate" name="recommend_goods_rate" value="<?php echo $output['list_setting']['recommend_goods_rate'];?>" class="input-txt">
          <span class="err">%</span>
          <p class="notic">保留两位有效数字。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){$("#submitBtn").click(function(){
    if($("#settingForm").valid()){
      $("#settingForm").submit();
	}
	});
});
</script>
