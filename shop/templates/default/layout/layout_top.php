<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.lang {
  width: 50px;
  margin-left: 15px;
  cursor: default;
}
.lang_list {
  display: none;
  position: absolute;
  width: 108px;
  border: 1px solid #e2e2e2;
  color: #333;
  list-style: none;
}
.lang_list li {
  background-color: #fff;
  padding: 5px 10px;
  cursor: default;
}
.lang_list li img{
  margin-right: 5px;
}
</style>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php if ($output['hidden_ncToolbar'] != 1) {?>
<div id="vToolbar" class="nc-appbar">
  <div class="nc-appbar-tabs" id="appBarTabs">
    <?php if ($_SESSION['is_login']) {?>
    <div class="user ta_delay" nctype="a-barUserInfo">
      <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/></div>
      <p>我</p>
    </div>
    <div class="user-info" nctype="barUserInfo" style="display:none;"><i class="arrow"></i>
      <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/>
        <div class="frame"></div>
      </div>
      <dl>
        <dt>Hi, <a href="<?php echo urlShop('member','home');?>"><?php echo $_SESSION['member_name'];?></a></dt>
        <dd>当前等级：<strong nctype="barMemberGrade"><?php echo $output['member_info']['level_name'];?></strong></dd>
        <dd>当前经验值：<strong nctype="barMemberExp"><?php echo $output['member_info']['member_exppoints'];?></strong></dd>
      </dl>
    </div>
    <?php } else {?>
    <div class="user ta_delay" nctype="a-barLoginBox">
      <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/></div>
      <p>未登录</p>
    </div>
    <div class="user-login-box" nctype="barLoginBox" style="display:none;"> <i class="arrow"></i> <a href="javascript:void(0);" class="close-a" nctype="close-barLoginBox" title="关闭">X</a>
      <form id="login_form" method="post" action="<?php echo urlLogin('login', 'login');?>" onsubmit="ajaxpost('login_form', '', '', 'onerror')">
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash('login','index');?>" />
        <dl>
          <dt><strong>登录账号</strong></dt>
          <dd>
            <input type="text" class="text" tabindex="1" autocomplete="off"  name="user_name" autofocus >
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt><strong>登录密码</strong><a href="<?php echo urlLogin('login', 'forget_password');?>" target="_blank">忘记登录密码？</a></dt>
          <dd>
            <input tabindex="2" type="password" class="text" name="password" autocomplete="off">
            <label></label>
          </dd>
        </dl>
        <?php if(C('captcha_status_login') == '1') { ?>
        <dl>
          <dt><strong>验证码</strong><a href="javascript:void(0)" class="ml5" onclick="javascript:document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash('login','index');?>&t=' + Math.random();">更换验证码</a></dt>
          <dd>
            <input tabindex="3" type="text" name="captcha" autocomplete="off" class="text w130" id="captcha2" maxlength="4" size="10" />
            <img src="" name="codeimage" border="0" id="codeimage" class="vt">
            <label></label>
          </dd>
        </dl>
        <?php } ?>
            <div class="bottom">
              <input type="submit" class="submit" value="确认">
              <input type="hidden" value="" name="ref_url">
              <a href="<?php echo urlLogin('login', 'register', array('ref_url' => $_GET['ref_url']));?>" target="_blank">注册新用户</a>
              <?php if (C('qq_isuse') == 1 || C('sina_isuse') == 1 || C('weixin_isuse') == 1){?>
              <h4><?php echo $lang['nc_otherlogintip'];?></h4>
              <?php if (C('weixin_isuse') == 1){?>
              <a href="javascript:void(0);" onclick="ajax_form('weixin_form', '微信账号登录', '<?php echo urlLogin('connect_wx', 'index');?>', 360);" title="微信账号登录" class="mr20">微信</a>
              <?php } ?>
              <?php if (C('sina_isuse') == 1){?>
              <a href="<?php echo MEMBER_SITE_URL;?>/index.php?act=connect_sina" title="新浪微博账号登录" class="mr20">新浪微博</a>
              <?php } ?>
              <?php if (C('qq_isuse') == 1){?>
              <a href="<?php echo MEMBER_SITE_URL;?>/index.php?act=connect_qq" title="QQ账号登录" class="mr20">QQ账号</a>
              <?php } ?>
              <?php } ?>
          </div>
      </form>
    </div>
    <?php }?>
    <ul class="tools">
    <?php if(C('node_chat')){ ?>
      <li><a href="javascript:void(0);" id="chat_show_user" class="chat ta_delay"><div class="tools_img"></div><span>聊天</span><i id="new_msg" class="new_msg" style="display:none;"></i></a></li>
        <?php } else {?>
         <li><a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $output['setting_config']['hao_qq']; ?>&amp;site=qq&amp;menu=yes" id="chat_show_user" class="chat ta_delay"><div class="tools_img"></div><span>QQ客服</span><i id="new_msg" class="new_msg" style="display:none;"></i></a></li>
     <?php } ?>
      <?php if (!$output['hidden_rtoolbar_cart']) { ?>
      <li><a href="javascript:void(0);" id="rtoolbar_cart" class="cart ta_delay"><div class="tools_img"></div><span>购物车</span><i id="rtoobar_cart_count" class="new_msg" style="display:none;"></i></a></li>
      <?php } ?>
      <?php if (!$output['hidden_rtoolbar_compare']) { ?>
      <li><a href="javascript:void(0);" id="compare" class="compare ta_delay"><div class="tools_img"></div><span>对比</span></a></li>
      <?php } ?>
      <li><a href="javascript:void(0);" id="gotop" class="gotop ta_delay"><div class="tools_img"></div><span>顶部</span></a></li>
    </ul>
    <div class="content-box" id="content-compare">
      <div class="top">
        <h3>商品对比</h3>
        <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
      <div id="comparelist"></div>
    </div>
    <div class="content-box" id="content-cart">
      <div class="top">
        <h3>我的购物车</h3>
        <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
      <div id="rtoolbar_cartlist"></div>
    </div>
    <a id="activator" href="javascript:void(0);" class="nc-appbar-hide"></a> </div>
  <div class="nc-hidebar" id="ncHideBar">
    <div class="nc-hidebar-bg">
      <?php if ($_SESSION['is_login']) {?>
      <div class="user-avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/></div>
      <?php } else {?>
      <div class="user-avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/></div>
      <?php }?>
      <div class="frame"></div>
      <div class="show"></div>
  </div>
</div>
</div>
<?php } ?>
<?php if ($output['setting_config']['hao_top_banner_status']>0 && $output['index_sign'] == 'index' && $output['index_sign'] != '0'){ ?>
<div style=" background:<?php echo $output['setting_config']['hao_top_banner_color']; ?>;">
  <div class="wrapper" id="top-banner" style="display: none;">
      <a href="javascript:void(0);" class="close" title="关闭"></a>
      <a href="<?php echo $output['setting_config']['hao_top_banner_url']; ?>" title="<?php echo $output['setting_config']['hao_top_banner_name']; ?>"><img border="0" src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['hao_top_banner_pic']; ?>" alt=""></a>
  </div>
</div>
<?php } ?>
<div class="public-top-layout w">
  <div class="topbar wrapper">
    <div class="service fl">
      <div class="tel"><?php echo $lang['hao_phone'];?><b><?php echo $output['setting_config']['hao_phone']; ?></b></div>
      <div class="lang_wrap">
        <div class="lang lang_btn">简体中文<span class="icon icon_CHN"></span><span class="icon icon_arrow_down"></span>
        </div>
        <ul class="lang_list">
          <li><img src="icon_CHN.png">English</li>
          <li><img src="icon_CHN.png">繁体中文</span></li>
          <li class="cur"><img src="icon_CHN.png">简体中文</li>
        </ul>
      </div>
      <div class="m-mx"> <span><i></i><a href="<?php echo WAP_SITE_URL;?>"><?php echo $lang['hao_wap'];?></a></span>
        <div>
          <?php if (C('mobile_isuse') && C('mobile_app')){?>
          <dl class="down_app">
            <dd>
              <div class="qrcode"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('mobile_app');?>" width="120" height="120"></div>
              <div class="hint">
                <h4><?php echo $lang['hao_mobile_client'];?></h4>
                <?php echo $lang['hao_scancode'];?></div>
              <div class="addurl">
                <?php if (C('mobile_apk')){?>
                <a href="<?php echo C('mobile_apk');?>" target="_blank"><i class="icon-android"></i>Android</a>
                <?php } ?>
                <?php if (C('mobile_ios')){?>
                <a href="<?php echo C('mobile_ios');?>" target="_blank"><i class="icon-apple"></i>iPhone</a>
                <?php } ?>
              </div>
            </dd>
          </dl>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="quick-menu">
      <dl>
        <dt><?php echo $lang['hao_service'];?><i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 2));?>"><?php echo $lang['hao_help'];?></a></li>
            <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 5));?>"><?php echo $lang['hao_customer'];?></a></li>
            <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 6));?>"><?php echo $lang['hao_custom'];?></a></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt><a href="<?php echo urlShop('show_joinin','index');?>" title="<?php echo $lang['hao_seller'];?>"><?php echo $lang['hao_seller'];?></a><i></i></dt>
        <dd>
          <ul>
		    <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=show_joinin&op=index" title="<?php echo $lang['hao_enter'];?>"><?php echo $lang['hao_enter'];?></a></li>
            <li><a href="<?php echo urlShop('seller_login','show_login');?>" target="_blank" title="<?php echo $lang['hao_seller_login'];?>"><?php echo $lang['hao_seller_login'];?></a></li>
          </ul>
	 </dd>
      </dl>
      <?php
      if(!empty($output['nav_list']) && is_array($output['nav_list'])){
	      foreach($output['nav_list'] as $nav){
	      if($nav['nav_location']<1){
	      	$output['nav_list_top'][] = $nav;
	      }
	      }
      }
      if(!empty($output['nav_list_top']) && is_array($output['nav_list_top'])){
      	?>
      <dl>
        <dt><?php echo $lang['hao_site_nav'];?><i></i></dt>
        <dd>
          <ul>
            <?php foreach($output['nav_list_top'] as $nav){?>
            <li><a
        <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        echo ' href="';
        switch($nav['nav_type']) {
        	case '0':echo $nav['nav_url'];break;
        	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
        	case '2':echo urlMember('article', 'article', array('ac_id'=>$nav['item_id']));break;
        	case '3':echo urlShop('activity', 'index', array('activity_id'=>$nav['item_id']));break;
        }
        echo '"';
        ?>><?php echo $nav['nav_title'];?></a></li>
            <?php }?>
          </ul>
        </dd>
      </dl>
      <?php } ?>
      </dl>
    </div>
    <div class="head-user-mall">
    <dl class="my-mall">
        <dt><span class="ico"></span><a href="<?php echo urlShop('member', 'home');?>" title="<?php echo $lang['hao_menber'];?>"><?php echo $lang['hao_menber'];?></a><i class="arrow"></i></dt>
        <dd>
          <div class="user-centent-menu">
            <ul>
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?act=member_message&op=message"><?php echo $lang['hao_msg'];?>(<span><?php echo $output['message_num']>0 ? $output['message_num']:'0';?></span>)</a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order" class="arrow"><?php echo $lang['hao_order'];?><i></i></a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_consult&op=my_consult"><?php echo $lang['hao_consult'];?>(<span id="member_consult">0</span>)</a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorite_goods&op=fglist" class="arrow"><?php echo $lang['hao_house'];?><i></i></a></li>
              <?php if (C('voucher_allow') == 1){?>
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?act=member_voucher"><?php echo $lang['hao_voucher'];?>(<span id="member_voucher">0</span>)</a></li>
              <?php } ?>
              <?php if (C('points_isuse') == 1){ ?>
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?act=member_points" class="arrow"><?php echo $lang['hao_points'];?><i></i></a></li>
              <?php } ?>
            </ul>
          </div>
          <div class="browse-history">
            <div class="part-title">
              <h4><?php echo $lang['hao_browse'];?></h4>
              <span style="float:right;"><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_goodsbrowse&op=list"><?php echo $lang['hao_history'];?></a></span> </div>
            <ul>
              <li class="no-goods"><img class="loading" src="<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif" /></li>
            </ul>
          </div>
        </dd>
      </dl>
    </div>
    <div class="user-entry">
      <?php echo $lang['nc_hello'];?>, <?php if($_SESSION['is_login'] == '1'){?>
      <span> <a href="<?php echo urlShop('member','home');?>"><?php echo $_SESSION['member_name'];?></a>
      <?php if ($output['member_info']['level_name']){ ?>
      <div class="nc-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
      <?php } ?>
      </span><span class="wr"><a href="<?php echo urlLogin('login','logout');?>"><?php echo $lang['nc_logout'];?></a></span>
      <?php }else{?>
      <span class="wr"><a class="login" href="<?php echo urlMember('login');?>">请<?php echo $lang['nc_login'];?></a> <a href="<?php echo urlLogin('login','register');?>"><?php echo $lang['nc_register'];?></a></span>
      <?php }?>
      <span><a href="<?php echo BASE_SITE_URL;?>/index.php?act=invite"><?php echo $lang['hao_invite'];?></a></span>
    </div>
  </div>
</div>

<script type="text/javascript">	
//登录开关
var connect_qq = "<?php echo C('qq_isuse')?>";
var connect_sn = "<?php echo C('sina_isuse')?>";
var connect_wx = "<?php echo C('weixin_isuse')?>";
var connect_weixin_appid = "<?php echo C('weixin_appid');?>";

$(function() {
	$('#gotop').click(function(){
	        $('html, body').animate({
	            scrollTop: 0
	        }, 500);
	});
	//顶部banner
	var haokey = getCookie('haokey');
		if(haokey){
		$("#top-banner").hide();
		} else {
			$("#top-banner").slideDown(800);
			}
		$("#top-banner .close").click(function(){
			setCookie('haokey','yes',1);
			$("#top-banner").hide();
	});
	//我的商城
		$(".head-user-mall dl").hover(function() {
			$(this).addClass("hover");
		},
		function() {
			$(this).removeClass("hover");
		});
		$('.head-user-mall .my-mall').mouseover(function(){// 最近浏览的商品
			load_history_information();
			$(this).unbind('mouseover');
		});
	
		$('#activator').click(function() {
			$('#content-cart').animate({'right': '-250px'});
			$('#content-compare').animate({'right': '-250px'});
			$('#vToolbar').animate({'right': '-60px'}, 300,
			function() {
				$('#ncHideBar').animate({'right': '59px'},	300);
			});
	        $('div[nctype^="bar"]').hide();
		});
		$('#ncHideBar').click(function() {
			$('#ncHideBar').animate({
				'right': '-86px'
			},
			300,
			function() {
				$('#content-cart').animate({'right': '-250px'});
				$('#content-compare').animate({'right': '-250px'});
				$('#vToolbar').animate({'right': '6px'},300);
			});
		});
    $("#compare").click(function(){
    	if ($("#content-compare").css('right') == '-250px') {
 		   loadCompare(false);
 		   $('#content-cart').animate({'right': '-250px'});
  		   $("#content-compare").animate({right:'0px'});
    	} else {
    		$(".close").click();
    		$(".chat-list").css("display",'none');
        }
	});
    $("#rtoolbar_cart").click(function(){
        if ($("#content-cart").css('right') == '-250px') {
         	$('#content-compare').animate({'right': '-250px'});
    		$("#content-cart").animate({right:'0px'});
    		if (!$("#rtoolbar_cartlist").html()) {
    			$("#rtoolbar_cartlist").load('index.php?act=cart&op=ajax_load&type=html');
    		}
        } else {
        	$(".close").click();
        	$(".chat-list").css("display",'none');
        }
	});
	$(".close").click(function(){
		$(".content-box").animate({right:'-250px'});
      });

	$(".quick-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});

    // 右侧bar用户信息
    $('div[nctype="a-barUserInfo"]').click(function(){
        $('div[nctype="barUserInfo"]').toggle();
    });
    // 右侧bar登录
    $('div[nctype="a-barLoginBox"]').click(function(){
        $('div[nctype="barLoginBox"]').toggle();
        document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash('login','index');?>&t=' + Math.random();
    });
    $('a[nctype="close-barLoginBox"]').click(function(){
        $('div[nctype="barLoginBox"]').toggle();
    });
    <?php if ($output['cart_goods_num'] > 0) { ?>
    $('#rtoobar_cart_count').html(<?php echo $output['cart_goods_num'];?>).show();
    <?php } ?>
    });

  $('body').on('click','.lang_btn',function(e){
      var option = $('.lang_wrap .lang_list');
      if(option.css('display') == 'none'){
        option.slideDown();
      }else{
        option.slideUp();
      }
      e.stopPropagation();
    });

    // 点击选项时赋值
    $('.lang_list li').on('click',function(e){
      $('.lang_list li').removeClass('cur');
      $(this).addClass('cur');
      $('.lang').html($(this).html()+'<span class="icon icon_arrow_down lang_btn"></span>');
      $('.lang_list').slideUp();
    });
</script>