<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>货币兑换率设置</h3>
        <h5></h5>
      </div>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="MYR_rate">马来西亚币兑换率</label>
        </dt>
        <dd class="opt">
          <input type="text" id="MYR_rate" name="MYR_rate" value="<?php echo $output['list_setting']['MYR_rate'];?>" class="input-txt">
          <span class="err">%</span>
          <p class="notic">保留两位有效数字。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="TWD_rate">台币兑换率</label>
        </dt>
        <dd class="opt">
          <input type="text" id="TWD_rate" name="TWD_rate" value="<?php echo $output['list_setting']['TWD_rate'];?>" class="input-txt">
          <span class="err">%</span>
          <p class="notic">保留两位有效数字。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="usd_rate">美金兑换率</label>
        </dt>
        <dd class="opt">
          <input type="text" id="usd_rate" name="usd_rate" value="<?php echo $output['list_setting']['usd_rate'];?>" class="input-txt">
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
