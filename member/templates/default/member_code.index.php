<?php defined('In33hao') or exit('Access Invalid!');?>

 <div id="qrcode" style="position: relative;left: 40%;margin-top: 60px;"></div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/qrcode.js"></script> 
<script type="text/javascript">
$(function(){
  // alert();
        // 创建二维码
        qrcode = new QRCode(document.getElementById("qrcode"), {
            width : 100,//设置宽高
            height : 100
        });

        qrcode.makeCode('https://www.baidu.com/?tn=monline_3_dg');
    
});
</script>
