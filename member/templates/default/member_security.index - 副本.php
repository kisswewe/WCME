<?php defined('In33hao') or exit('Access Invalid!');?>
<!-- <link rel="stylesheet" type="text/css" href="<?php echo LOGIN_TEMPLATES_URL;?>/css/home_login.css"> -->
<style type="text/css">
  .teaoPoint{
    display: none;
  }
  .teaoPoint i{
    color: #f40;
  }
  .bind_bg{
    display: none;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0,0,0,0.3);
    z-index: 1200;
  }
  .bind_bg .nc-register-box{
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
  }
  .bind_bg .nc-register-mode .tabs-content{
    padding: 0 80px 0 100px;
  }
  .bind_bg .dialog_close_button{
    top: 20px;
    right: 20px;
    font-size: 24px;
  }
  .account_title{
    line-height: 46px;
    font-size: 16px;
    margin-top: 10px;
  }
  .tabs-nav {
    font-size: 0;
    word-spacing: -1em;
    border-bottom: solid 1px #E6E6E6;
}
.tabs-container {
    position: relative;
    z-index: 1;
}
.nc-login-form {
    display: block;
}
.account_title {
    line-height: 46px;
    font-size: 16px;
    margin-top: 10px;
}
.nc-login-form dl {
    background-color: #FFF;
    width: 398px;
    height: 52px;
    margin-top: -1px;
    border: solid 1px #E6E6E6;
    position: relative;
    z-index: 1;
}
.nc-login-form dl dt {
    font-size: 14px;
    line-height: 20px;
    color: #666;
    width: 72px;
    padding: 16px 0 16px 20px;
    float: left;
}
.nc-login-form dl dd {
    height: 28px;
    float: left;
    padding: 12px 0;
}
.nc-login-form dl dd .text {
    font-family: "microsoft yahei";
    font-size: 14px;
    line-height: 28px;
    width: 290px;
    height: 28px;
    padding: 0;
    border: none 0;
}
input::-webkit-input-placeholder, textarea::-webkit-input-placeholder {color: #999; }
input:-moz-placeholder, textarea:-moz-placeholder {color: #999; }
input::-moz-placeholder, textarea::-moz-placeholder {color: #999; }
input:-ms-input-placeholder, textarea:-ms-input-placeholder {color: #999; }


.bind_bg {
    display: none;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0,0,0,0.3);
    z-index: 1200;
}
.nc-register-box {
    filter: progid:DXImageTransform.Microsoft.gradient(enabled='true',startColorstr='#26000000', endColorstr='#26000000');
    background: rgba(0,0,0,0.15);
    width: 860px;
    height: 560px;
    padding: 10px;
    margin: 60px auto;
}
.nc-register-layout {
    background-color: #FFF;
    display: block;
    width: 820px;
    height: 520px;
    padding: 19px;
    border: solid 1px #CCC;
    overflow: hidden;
}
.nc-register-layout .left {
    width: 600px;
    height: 520px;
    float: left;
}
.nc-register-mode {
    width: 580px;
}
</style>
<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-security-user">
    <h3>您的账户信息</h3>
    <div class="user-avatar"><span><img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>"></span></div>
    <div class="user-intro">
      <dl>
        <dt>登录账号：</dt>
        <dd><?php echo $output['member_info']['member_name'];?></dd>
      </dl>
      <dl>
        <dt>绑定邮箱：</dt>
        <dd><?php echo encryptShow($output['member_info']['member_email'],4,4);?></dd>
      </dl>
      <dl>
        <dt>手机号码：</dt>
        <dd><?php echo encryptShow($output['member_info']['member_mobile'],4,4);?></dd>
      </dl>
      <dl>
        <dt>上次登录：</dt>
        <dd><?php echo date('Y年m月d日 H:i:s',$output['member_info']['member_old_login_time']);?>&#12288;|&#12288;IP地址:<?php echo $output['member_info']['member_old_login_ip'];?>&nbsp;<span>（不是您登录的？请立即<a href="index.php?act=member_security&op=auth&type=modify_pwd">“更改密码”</a>）。</span></dd>
      </dl>
    </div>
  </div>
  <div class="ncm-security-container">
    <div class="title">您的安全服务</div>
    <?php if ($output['member_info']['security_level'] <= 1) { ?>
    <div class="current low">当前安全等级：<strong>低</strong><span>(建议您开启全部安全设置，以保障账户及资金安全)</span></div>
    <?php } else if ($output['member_info']['security_level'] == 2) { ?>
    <div class="current normal">当前安全等级：<strong>中</strong><span>(建议您开启全部安全设置，以保障账户及资金安全)</span></div>
    <?php } else { ?>
    <div class="current high">当前安全等级：<strong>高</strong><span>(您目前账户运行很安全)</span></div>
    <?php } ?>

    <dl id="password" class="yes">
      <dt><span class="icon"><i></i></span><span class="item">
        <h4>登录密码</h4>
        <h6>已设置</h6>
        </span></dt>
      <dd><span class="explain">安全性高的密码可以使账号更安全。建议您定期更换密码，且设置一个包含数字和字母，并长度超过6位以上的密码，为保证您的账户安全，只有在您绑定邮箱或手机后才可以修改密码。</span><span class="handle"><a href="index.php?act=member_security&op=auth&type=modify_pwd" class="ncbtn  ncbtn-bittersweet">修改密码</a></span></dd>
    </dl>
    <dl id="email" class="<?php echo $output['member_info']['member_email_bind'] == 1 ? 'yes' : 'no';?>">
      <dt><span class="icon"><i></i></span><span class="item">
        <h4>邮箱绑定</h4>
        <h6><?php echo $output['member_info']['member_email_bind'] == 1 ? '已绑定' : '未绑定';?></h6>
        </span></dt>
      <dd><span class="explain">进行邮箱验证后，可用于接收敏感操作的身份验证信息，以及订阅更优惠商品的促销邮件。</span><span class="handle"><a href="index.php?act=member_security&op=auth&type=modify_email" class="ncbtn ncbtn-aqua bd">绑定邮箱</a><a href="index.php?act=member_security&op=auth&type=modify_email" class="ncbtn ncbtn-bittersweet jc">修改邮箱</a></span></dd>
    </dl>
    <dl id="mobile" class="<?php echo $output['member_info']['member_mobile_bind'] == 1 ? 'yes' : 'no';?>">
      <dt><span class="icon"><i></i></span><span class="item">
        <h4>手机绑定</h4>
        <h6><?php echo $output['member_info']['member_mobile_bind'] == 1 ? '已绑定' : '未绑定';?></h6>
        </span></dt>
      <dd><span class="explain">进行手机验证后，可用于接收敏感操作的身份验证信息，以及进行积分消费的验证确认，非常有助于保护您的账号和账户财产安全。</span><span class="handle"><a href="index.php?act=member_security&op=auth&type=modify_mobile" class="ncbtn ncbtn-aqua bd">绑定手机</a><a href="index.php?act=member_security&op=auth&type=modify_mobile" class="ncbtn ncbtn-bittersweet jc">修改手机</a></span></dd>
    </dl>
    <dl id="paypwd" class="<?php echo $output['member_info']['member_paypwd'] != ''  ? 'yes' : 'no';?>">
      <dt><span class="icon"><i></i></span><span class="item">
        <h4>支付密码</h4>
        <h6><?php echo $output['member_info']['member_paypwd'] != '' ? '已设置' : '未设置';?></h6>
        </span></dt>
      <dd><span class="explain">设置支付密码后，在使用账户中余额时，需输入支付密码。</span><span class="handle"><a href="index.php?act=member_security&op=auth&type=modify_paypwd" class="ncbtn ncbtn-aqua bd">设置密码</a><a href="index.php?act=member_security&op=auth&type=modify_paypwd" class="ncbtn ncbtn-bittersweet jc">修改密码</a></span></dd>
    </dl>
    <dl id="mobile" class="<?php echo $output['member_info']['member_mobile_bind'] == 1 ? 'yes' : 'no';?>">
      <dt><span class="icon"><i></i></span><span class="item">
        <h4>中控帐号</h4>
        <h6 id="isBind"></h6>
        </span></dt>
      <dd><span class="explain">
        绑定中控帐号，可进行积分兑换
      </span><span class="handle"><a class="ncbtn ncbtn-aqua bd" id="bindAccount">绑定帐号</a><!-- <a href="index.php?act=member_security&op=auth&type=modify_mobile" class="ncbtn ncbtn-bittersweet jc">修改帐号</a> --></span></dd>
    </dl>
  </div>
</div>
<div class="bind_bg">
  <div class="nc-register-box">
    <span class="dialog_close_button" id="hide_bind_window">X</span>
    <div class="nc-register-layout">
      <div class="left">
        <div class="nc-register-mode">
          <ul class="tabs-nav">
            <li><a href="#default">使用积分请先绑定中控帐号<i></i></a></li>
          </ul>
          <div class="tabs-container">
            <div class="tabs-content">
            <form></form>
              <form method="post" class="nc-login-form" id="bindForm">
                <h3 class="account_title">315商城绑定帐号信息</h3>
                <dl>
                  <dt>帐号：</dt>
                  <dd>
                    <input type="text" name="member_name" class="text" placeholder="请输入315商城帐号"/>
                  </dd>
                </dl>
                <dl>
                  <dt>密码：</dt>
                  <dd>
                    <input type="password" name="member_passwd" class="text" placeholder="请输入315商城密码"/>
                  </dd>
                </dl>
                <h3 class="account_title">中控绑定帐号信息</h3>
                <dl class="mt15">
                  <dt>帐号：</dt>
                  <dd>
                    <input type="text" name="account" class="text" placeholder="请输入中控帐号"/>
                  </dd>
                </dl>
                <dl>
                  <dt>密码：</dt>
                  <dd>
                    <input type="password" name="password" class="text" placeholder="请输入中控密码"/>
                  </dd>
                </dl>
                <?php if(C('captcha_status_register') == '1') { ?>
                <div class="code-div mt15">
                  <dl>
                    <dt>验证码：</dt>
                    <dd>
                      <input type="text" id="captcha" name="captcha" class="text w80" size="10" placeholder="输入验证码" />
                    </dd>
                  </dl>
                  <span><img src="index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>" name="codeimage" id="codeimage"/> <a class="makecode" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_password_change_code']; ?></a></span></div>
                <?php } ?>
                <!-- <dl class="clause-div">
                  <dd>
                    <input name="agree" type="checkbox" class="checkbox" id="clause" value="1" checked="checked" />
                    <?php echo $lang['login_register_agreed'];?><a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="agreement" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a></dd>
                </dl> -->
                <div class="submit-div">
                  <input type="button" id="bindSubmit" value="立即绑定" class="submit"/>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="reister-after">
          <h4>绑定之后您可以</h4>
          <ol>
            <li class="ico01"><i></i>兑换积分支付订单</li>
            <li class="ico03"><i></i>安全交易诚信无忧</li>
            <li class="ico04"><i></i>积分获取优惠购物</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    var $url = "../../shop/index.php?act=buy&op=theo_account";
    var $data = {};
    $.post($url,$data,function(data){
      if(data){
        if(data.code == 200){
          $('#isBind').text('已绑定');
          $('#bindAccount').text('已绑定').css('backgroundColor','#ccc');
        }else if(data.code == 400){
          $('#bindAccount').on('click',function(){
            $('.bind_bg').show();
          });
          $('#isBind').text('未绑定');
          $('#bindAccount').on('click',function(){
            $('.bind_bg').show();
          });
        }
      }
    },'json');

    //隐藏绑定中控窗口
    $('#hide_bind_window').on('click',function(){
      $('.bind_bg').hide();
      $('#choseTeao')[0].checked = false;
    });

    //绑定中控
    $('#bindSubmit').on('click',function(){
      $('#bindForm').submit(false);
      var member_name = $('input[name="member_name"]').val();
      var member_passwd = $('input[name="member_passwd"]').val();
      var account = $('input[name="account"]').val();
      var password = $('input[name="password"]').val();

      if(member_name == '' || member_passwd == '' || account == '' || password == ''){
          showDialog('请填写帐号密码', 'error','','','','','','','','',3);
          return;        
      }

      var captcha = $('#captcha').val();
      $.get('index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>&captcha='+captcha,function(data){
        if(!!data){
          var $url = "../../shop/index.php?act=buy&op=binding_operation";
          var $data = {member_name:member_name,member_passwd:member_passwd,account:account,password:password};
          $.post($url,$data,function(data){
            if(data){
              if(data.code == 200){
                showDialog('绑定成功', 'succ','','','','','','','','',2);
                $('.bind_bg').hide();
                // $('input[name="theo_account"]').val(data.theo_account);
                // $('#teaoPoint').text(data.points);
                // $('#tesePoint').text(calcPoint(data.points));
                // setTimeout(function(){
                //   $('.bind_bg').hide();
                //   $('.teaoPoint').show().find('input[name="converPoint"]').removeAttr('disabled');
                // },2000);
              }else{
                showDialog(data.message, 'error','','','','','','','','',3);
              }
            }
          },'json');
        }else{
          showDialog('验证码错误', 'error','','','','','','','','',3);
          document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();
          return;
        }
      },'json');
    })
</script>