var page = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = '';
	
$(function(){
	var key = getCookie('key');
	if(!key){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
	}

	if (getQueryString('data-state') != '') {
	    $('#filtrate_ul').find('li').has('a[data-state="' + getQueryString('data-state')  + '"]').addClass('selected').siblings().removeClass("selected");
	}

    $('#search_btn').click(function(){
        reset = true;
    	initPage();
    });

    $('#fixed_nav').waypoint(function() {
        $('#fixed_nav').toggleClass('fixed');
    }, {
        offset: '50'
    });

	function initPage(){
	    if (reset) {
	        curpage = 1;
	        hasMore = true;
	    }
        $('.loading').remove();
        if (!hasMore) {
            return false;
        }
        hasMore = false;
	    var state_type = $('#filtrate_ul').find('.selected').find('a').attr('data-state');
	    var orderKey = $('#order_key').val();
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=member_order&op=order_list&page="+page+"&curpage="+curpage,
			data:{key:key, state_type:state_type, order_key : orderKey},
			dataType:'json',
			success:function(result){
				checkLogin(result.login);//检测是否登录了
				curpage++;
                hasMore = result.hasmore;
                if (!hasMore) {
                    get_footer();
                }
                if (result.datas.order_group_list.length <= 0) {
                    $('#footer').addClass('posa');
                } else {
                    $('#footer').removeClass('posa');
                }
				var data = result;
				data.WapSiteUrl = WapSiteUrl;//页面地址
				data.ApiUrl = ApiUrl;
				data.key = getCookie('key');
				template.helper('$getLocalTime', function (nS) {
                    var d = new Date(parseInt(nS) * 1000);
                    var s = '';
                    s += d.getFullYear() + '年';
                    s += (d.getMonth() + 1) + '月';
                    s += d.getDate() + '日 ';
                    s += d.getHours() + ':';
                    s += d.getMinutes();
                    return s;
				});
                template.helper('p2f', function(s) {
                    return (parseFloat(s) || 0).toFixed(2);
                });
                template.helper('parseInt', function(s) {
                    return parseInt(s);
                });
				var html = template.render('order-list-tmpl', data);
				if (reset) {
				    reset = false;
				    $("#order-list").html(html);
				} else {
                    $("#order-list").append(html);
                }
			}
		});

	}
	

    // 取消
    $('#order-list').on('click','.cancel-order', cancelOrder);
    // 删除
    $('#order-list').on('click','.delete-order',deleteOrder);
    // 收货
    $('#order-list').on('click','.sure-order',sureOrder);
    // 评价
    $('#order-list').on('click','.evaluation-order',evaluationOrder);
    // 追评
    $('#order-list').on('click','.evaluation-again-order', evaluationAgainOrder);

    $('#order-list').on('click','.viewdelivery-order',viewOrderDelivery);

    $('#order-list').on('click','.check-payment',function() {
        getTeao();
        var pay_sn = $(this).attr('data-paySn');
        toPay(pay_sn,'member_buy','pay');
        return false;
    });

    /************** 积分 Begin ****************/
    // 绑定帐号
    $.animationLeft({
        valve : '#hint_binding',
        wrapper : '#bind-teao-wrapper',
        scroll : ''
    });
    // 积分
    $('#useIntegral').on('change',function(){
        if(this.checked){
            $('#wrapperIntegral').show();
        }else{
            $('#wrapperIntegral').hide();
            $('#integralAmount').val('');
        }
    });

    // 输入积分限制大小
    $('#integralAmount').on('input',function(){
      var point = Number($('#availableIntegral').text());
      var $this = $(this).val();
      if($this > point) $(this).val(point);
      if($this < 0) $(this).val('');
      $('#converIntegraly').text($(this).val()?calcPoint($(this).val()):0);
    });
    //绑定福哈创特
    $('#bindSubmit').on('click',function(){
        var member_name = $('input[name="member_name"]').val();
        var member_passwd = $('input[name="member_passwd"]').val();
        var account = $('input[name="account"]').val();
        var password = $('input[name="password"]').val();

        if(member_name == '' || member_passwd == '' || account == '' || password == ''){
            errorTipsShow('请填写帐号密码');
            return;        
        }
        var $url = SiteUrl+"/index.php?act=buy&op=binding_operation";
        var $data = {member_name:member_name,member_passwd:member_passwd,account:account,password:password};
        $.post($url,$data,function(data){
          if(data){
            if(data.code == 200){
                errorTipsShow('绑定成功');
                $('#bind-teao-wrapper').addClass('right').removeClass('left');
                $('#useIntegral').removeAttr('disabled');
                $('#hint_availableIntegral').show();
                $('#hint_binding').hide();
                $('input[name="theo_account"]').val(data.theo_account);
                $('#availableIntegral').text(data.points);
            }else{
              errorTipsShow(data.message);
            }
          }
        },'json');
    })

    // 获取福哈创特商城积分
    function getTeao(){
      var $url = SiteUrl+"/index.php?act=buy&op=theo_account";
      var $data = {};
      $.post($url,$data,function(data){
        if(data){
          if(data.code == 200){
            $('#useIntegral').removeAttr('disabled');
            $('#hint_availableIntegral').show();
            $('#hint_binding').hide();
            $('#availableIntegral').text(data.points);
            $('input[name="theo_account"]').val(data.theo_account);
          }else if(data.code == 400){
            $('#useIntegral').attr('disabled','disabled');
            $('#hint_binding').show();
            $('#hint_availableIntegral').hide();
          }
        }
      },'json');
    }

    // 计算兑换积分
    function calcPoint(point){
      return (point*ratio*0.01).toFixed(2);
    }
    /************** 积分 End ****************/

    //取消订单
    function cancelOrder(){
        var order_id = $(this).attr("order_id");

        $.sDialog({
            content: '确定取消订单？',
            okFn: function() { cancelOrderId(order_id); }
        });
    }

    function cancelOrderId(order_id) {
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?act=member_order&op=order_cancel",
            data:{order_id:order_id,key:key},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                    reset = true;
                    initPage();
                } else {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                }
            }
        });
    }

    //删除订单
    function deleteOrder(){
        var order_id = $(this).attr("order_id");

        $.sDialog({
            content: '是否移除订单？<h6>电脑端订单回收站可找回订单！</h6>',
            okFn: function() { deleteOrderId(order_id); }
        });
    }

    function deleteOrderId(order_id) {
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?act=member_order&op=order_delete",
            data:{order_id:order_id,key:key},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                    reset = true;
                    initPage();
                } else {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                }
            }
        });
    }

    //确认订单
    function sureOrder(){
        var order_id = $(this).attr("order_id");

        $.sDialog({
            content: '确定收到了货物吗？',
            okFn: function() { sureOrderId(order_id); }
        });
    }

    function sureOrderId(order_id) {
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?act=member_order&op=order_receive",
            data:{order_id:order_id,key:key},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                    reset = true;
                    initPage();
                } else {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                }
            }
        });
    }
    
    // 评价
    function evaluationOrder() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/member_evaluation.html?order_id=' + orderId;
        
    }
    
    // 追加评价
    function evaluationAgainOrder() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/member_evaluation_again.html?order_id=' + orderId;
    }

    function viewOrderDelivery() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/order_delivery.html?order_id=' + orderId;
    }
    
    $('#filtrate_ul').find('a').click(function(){
        $('#filtrate_ul').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0,0);
        initPage();
    });

    //初始化页面
    initPage();
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            initPage();
        }
    });
});
function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({
            url: WapSiteUrl+'/js/tmpl/footer.js',
            dataType: "script"
          });
    }
}