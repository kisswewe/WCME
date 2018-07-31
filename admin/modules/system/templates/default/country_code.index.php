<?php defined('In33hao') or exit('Access Invalid!'); ?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="index.php?act=adv" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>地区区号</h3>
                <h5><?php echo $lang['adv_index_manage_subhead']; ?></h5>
            </div>
        </div>
    </div>
    <div id="flexigrid"></div>
</div>

<div id="LoginBox">
    <div class="row1 out_odb">
        导入(仅支持excel 2007) <a href="javascript:void(0)" title="关闭窗口" class="close_btn" id="closeBtn">×</a>
    </div>
    <form method="post"  id="formSearch" action="index.php?act=country_code&op=excel" enctype="multipart/form-data">
        <div class="row">
            导入Excel表: <span class="inputBox">
                <input type="file" id="txtName" placeholder="" name="area_list"/>
            </span><a href="javascript:void(0)" title="提示" class="warning" id="warn">*</a>
        </div>
        <div class="submit-btn">
            <input type="submit" value="导入">
        </div>
    </form>
</div>
<script>
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=country_code&op=get_xml',
            colModel: [
            //    {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
                {display: '国际名称', name: 'international_name', width: 150, sortable: false, align: 'left'},
                {display: '中文名称', name: 'chinese_name', width: 100, sortable: true, align: 'left'},
                {display: '国际代码', name: 'international_code', width: 120, sortable: false, align: 'left'},
                {display: '国际简码', name: 'international_brief', width: 100, sortable: true, align: 'center'},
                {display: '洲际', name: 'continent', width: 100, sortable: true, align: 'center'}
            ],
            buttons: [
//                {display: '<i class="fa fa-plus"></i>新增数据', name: 'add', bclass: 'add', title: '新增数据', onpress: fg_operation},
                {display: '<i class="fa fa-plus"></i>导入数据', name: 'excel', bclass: 'excel', title: '导入数据', onpress: fg_operation},
            ],
            searchitems: [
                {display: '中文名称', name: 'chinese_name'}
            ],
            sortname: "id",
            sortorder: "desc",
            title: '区号列表'
        });
    });
    function fg_delete(id) {
        if (typeof id == 'number') {
            var id = new Array(id.toString());
        }
        ;
        if (confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')) {
            id = id.join(',');
        } else {
            return false;
        }
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?act=country_code&op=delete",
            data: "id=" + id,
            success: function (data) {
                window.location.reload();
            }
        });
    }
    function fg_operation(name, bDiv) {
        if (name == 'add') {
            window.location.href = 'index.php?act=country_code&op=add';
        } else if (name == 'excel') {
            $("#LoginBox").fadeIn("slow");
        } else if (name == 'delete') {
            if ($('.trSelected', bDiv).length > 0) {
                var items = $('.trSelected', bDiv);
                var itemlist = new Array();
                $('.trSelected', bDiv).each(function () {
                    itemlist.push($(this).attr('data-id'));
                });
                fg_delete(itemlist);
            }
        }
    }
</script>


<style>
    .mask{margin:0;padding:0;border:none;width:100%;height:100%;background:#333;opacity:0.6;filter:alpha(opacity=60);z-index:9999;position:fixed;top:0;left:0;display:none;}
    #LoginBox{position:absolute;left:460px;top:150px;background:white;width:426px;height:282px;border:3px solid #444;border-radius:7px;z-index:10000;display:none;}
    .row1{background:#f7f7f7;padding:0px 20px;line-height:50px;height:50px;font-weight:bold;color:#666;font-size:20px;}
    .row{height:77px;line-height:77px;padding:0px 30px;font-family:华文楷体;font-size:x-large;}
    .submit-btn{padding-top: 100px;padding-left: 40px;}
    .close_btn{font-family:arial;font-size:30px;font-weight:700;color:#999;text-decoration:none;float:right;padding-right:4px;}
    .inputBox{padding:1px 3px 6px 3px;border-radius:5px;margin-left:5px;}
    #txtName{height:27px;width:230px;border:none;}
    #txtPwd{height:27px;width:230px;border:none;}
    #loginbtn{color:White;background:#4490f7;text-decoration:none;padding:10px 95px;margin-left:87px;margin-top:-10px;border-radius:5px;opacity:0.8;filter:alpha(opacity=80);}
    .warning{float:right;color:Red;text-decoration:none;font-size:20px;font-weight:bold;margin-right:20px;display:none;}
</style>
<script type="text/javascript">
    $(function ($) {

        //按钮的透明度
        $("#loginbtn").hover(function () {
            $(this).stop().animate({
                opacity: '1'
            }, 600);
        }, function () {
            $(this).stop().animate({
                opacity: '0.8'
            }, 1000);
        });
        //文本框不允许为空---按钮触发
        $("#loginbtn").on('click', function () {
            var txtName = $("#txtName").val();
            var txtPwd = $("#txtPwd").val();
            if (txtName == "" || txtName == undefined || txtName == null) {
                if (txtPwd == "" || txtPwd == undefined || txtPwd == null) {
                    $(".warning").css({display: 'block'});
                } else {
                    $("#warn").css({display: 'block'});
                    $("#warn2").css({display: 'none'});
                }
            } else {
                if (txtPwd == "" || txtPwd == undefined || txtPwd == null) {
                    $("#warn").css({display: 'none'});
                    $(".warn2").css({display: 'block'});
                } else {
                    $(".warning").css({display: 'none'});
                }
            }
        });
        //文本框不允许为空---单个文本触发
        $("#txtName").on('blur', function () {
            var txtName = $("#txtName").val();
            if (txtName == "" || txtName == undefined || txtName == null) {
                $("#warn").css({display: 'block'});
            } else {
                $("#warn").css({display: 'none'});
            }
        });
        $("#txtName").on('focus', function () {
            $("#warn").css({display: 'none'});
        });
        //
        $("#txtPwd").on('blur', function () {
            var txtName = $("#txtPwd").val();
            if (txtName == "" || txtName == undefined || txtName == null) {
                $("#warn2").css({display: 'block'});
            } else {
                $("#warn2").css({display: 'none'});
            }
        });
        $("#txtPwd").on('focus', function () {
            $("#warn2").css({display: 'none'});
        });
        //关闭
        $(".close_btn").hover(function () {
            $(this).css({color: 'black'})
        }, function () {
            $(this).css({color: '#999'})
        }).on('click', function () {
            $("#LoginBox").fadeOut("fast");
            $("#mask").css({display: 'none'});
        });
    });
</script>		