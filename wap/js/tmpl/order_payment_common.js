var key = getCookie('key');
var password,rcb_pay,pd_pay,payment_code,member_points,ratio;

// 获取积分兑换比例及用户积分
$.get(SiteUrl+"/index.php?act=buy&op=find_parameter",function(data){
    if(data){
        ratio = Number(data.integral_rate);
        member_points = Number(data.member_points);
        $('#ratio').text(ratio);
    }
},'json');

 // 现在支付方式
 function toPay(pay_sn,act,op) {
     $.ajax({
         type:'post',
         url:ApiUrl+'/index.php?act='+act+'&op='+op,
         data:{
             key:key,
             pay_sn:pay_sn
             },
         dataType:'json',
         success: function(result){
             checkLogin(result.login);
             if (result.datas.error) {
                 $.sDialog({
                     skin:"red",
                     content:result.datas.error,
                     okBtn:false,
                     cancelBtn:false
                 });
                 return false;
             }
             // 从下到上动态显示隐藏内容
             $.animationUp({valve:'',scroll:''});
             
             // 需要支付金额
             $('#onlineTotal').html(result.datas.pay_info.pay_amount);
             // 需要支付积分
             $('#onlineTotalIntegral').html(result.datas.pay_info.pay_integral);
             
             // 是否设置支付密码
             if (!result.datas.pay_info.member_paypwd) {
                 $('#wrapperPaymentPassword').find('.input-box-help').show();
             }
             
             // 支付密码标记
             var _use_password = false;
             if (parseFloat(result.datas.pay_info.payed_amount) <= 0) {
                 if (parseFloat(result.datas.pay_info.member_available_pd) == 0 && parseFloat(result.datas.pay_info.member_available_rcb) == 0) {
                     $('#internalPay').hide();
                 } else {
                     $('#internalPay').show();
                     // 充值卡
                     if (parseFloat(result.datas.pay_info.member_available_rcb) != 0) {
                         $('#wrapperUseRCBpay').show();
                         $('#availableRcBalance').html(parseFloat(result.datas.pay_info.member_available_rcb).toFixed(2));
                     } else {
                         $('#wrapperUseRCBpay').hide();
                     }
                     
                     // 预存款
                     if (parseFloat(result.datas.pay_info.member_available_pd) != 0) {
                         $('#wrapperUsePDpy').show();
                         $('#availablePredeposit').html(parseFloat(result.datas.pay_info.member_available_pd).toFixed(2));
                     } else {
                         $('#wrapperUsePDpy').hide();
                     }
                 }
             } else {
                 $('#internalPay').hide();
             }
             
             password = '';
             $('#paymentPassword').on('change', function(){
                 password = $(this).val();
             });

             rcb_pay = 0;
             $('#useRCBpay').click(function(){
                 if ($(this).prop('checked')) {
                     _use_password = true;
                     $('#wrapperPaymentPassword').show();
                     rcb_pay = 1;
                 } else {
                     if (pd_pay == 1) {
                         _use_password = true;
                         $('#wrapperPaymentPassword').show();
                     } else {
                         _use_password = false;
                         $('#wrapperPaymentPassword').hide();
                     }
                     rcb_pay = 0;
                 }
             });

             pd_pay = 0;
             $('#usePDpy').click(function(){
                 if ($(this).prop('checked')) {
                     _use_password = true;
                     $('#wrapperPaymentPassword').show();
                     pd_pay = 1;
                 } else {
                     if (rcb_pay == 1) {
                         _use_password = true;
                         $('#wrapperPaymentPassword').show();
                     } else {
                         _use_password = false;
                         $('#wrapperPaymentPassword').hide();
                     }
                     pd_pay = 0;
                 }
             });

             payment_code = '';
             if (!$.isEmptyObject(result.datas.pay_info.payment_list)) {
                 var readytoWXPay = false;
                 var readytoAliPay = false;
                 var m = navigator.userAgent.match(/MicroMessenger\/(\d+)\./);
                 if (parseInt(m && m[1] || 0) >= 5) {
                     // 微信内浏览器
                     readytoWXPay = true;
                 } else {
                     readytoAliPay = true;
                 }
                 for (var i=0; i<result.datas.pay_info.payment_list.length; i++) {
                     var _payment_code = result.datas.pay_info.payment_list[i].payment_code;
                     if (_payment_code == 'alipay' && readytoAliPay) {
                         $('#'+ _payment_code).parents('label').show();
                         if (payment_code == '') {
                             payment_code = _payment_code;
                             $('#'+_payment_code).attr('checked', true).parents('label').addClass('checked');
                         }
                     }
                     if (_payment_code == 'wxpay_jsapi' && readytoWXPay) {
                         $('#'+ _payment_code).parents('label').show();
                         if (payment_code == '') {
                             payment_code = _payment_code;
                             $('#'+_payment_code).attr('checked', true).parents('label').addClass('checked');
                         }
                     }
                 }
             }

             $('#alipay').click(function(){
                 payment_code = 'alipay';
             });
             
             $('#wxpay_jsapi').click(function(){
                 payment_code = 'wxpay_jsapi';
             });

             $('#toPay').click(function(){
                 if (payment_code == '') {
                     $.sDialog({
                         skin:"red",
                         content:'请选择支付方式',
                         okBtn:false,
                         cancelBtn:false
                     });
                     return false;
                 }

                 //检查积分
                var point = Number($('input[name="converIntegral"]').val());
                if (point < 0 || point > $('#availableIntegral').text()) {
                    $.sDialog({
                         skin:"red",
                         content:'兑换积分不能大于已有积分或小于0',
                         okBtn:false,
                         cancelBtn:false
                     });
                     return false;
                }

                 if (_use_password) {
                     // 验证支付密码是否填写
                     if (password == '') {
                         $.sDialog({
                             skin:"red",
                             content:'请填写支付密码',
                             okBtn:false,
                             cancelBtn:false
                         });
                         return false;
                     }
                     // 验证支付密码是否正确
                     $.ajax({
                         type:'post',
                         url:ApiUrl+'/index.php?act=member_buy&op=check_pd_pwd',
                         dataType:'json',
                         data:{key:key,password:password},
                         success:function(result){
                             if (result.datas.error) {
                                 $.sDialog({
                                     skin:"red",
                                     content:result.datas.error,
                                     okBtn:false,
                                     cancelBtn:false
                                 });
                                 return false;
                             }
                             // goToPayment(pay_sn,act == 'member_buy' ? 'pay_new' : 'vr_pay_new');
                             checkPoint();
                         }
                     });
                 } else {
                    checkPoint();
                 }

                 // 检查积分是否不足
                 function checkPoint(){
                    if($('#useIntegral')[0].checked){
                        if ((Number($('#integralAmount').text())+ member_points) < Number($('#onlineTotalIntegral').text())) {
                          $.sDialog({
                             skin:"red",
                             content:'积分不足',
                             okBtn:false,
                             cancelBtn:false
                          });
                          return false;
                        }
                        submitPoint(pay_sn,act == 'member_buy' ? 'pay_new' : 'vr_pay_new');
                    }else{
                        if (member_points < Number($('#onlineTotalIntegral').text())) {
                          $.sDialog({
                             skin:"red",
                             content:'积分不足',
                             okBtn:false,
                             cancelBtn:false
                          });
                          return false;
                        }
                        goToPayment(pay_sn,act == 'member_buy' ? 'pay_new' : 'vr_pay_new');
                    }
                 }

                //提交积分
                function submitPoint(pay_sn,act){
                    var theo_account = $('input[name="theo_account"]').val();
                    var points = $('#integralAmount').val();
                    var $url = SiteUrl+"/index.php?act=buy&op=int_trans";
                    var $data = {theo_account:theo_account,points:points};
                    $.post($url,$data,function(data){
                      if(data){
                        if(data.code == 200){
                            goToPayment(pay_sn,act);
                        }else{
                          $.sDialog({
                             skin:"red",
                             content:'系统错误',
                             okBtn:false,
                             cancelBtn:false
                          });    
                        }
                      }
                    },'json');
                }
             });
         }
     });
 }


 function goToPayment(pay_sn,op) {
     location.href = ApiUrl+'/index.php?act=member_payment&op='+op+'&key=' + key + '&pay_sn=' + pay_sn + '&password=' + password + '&rcb_pay=' + rcb_pay + '&pd_pay=' + pd_pay + '&payment_code=' + payment_code;
 }
