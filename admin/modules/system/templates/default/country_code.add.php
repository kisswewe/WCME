<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=document&op=document" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php if($_GET['id']){echo "编辑";}else{echo "新增";}?>地区区号</h3>
        <h5><?php echo $lang['document_index_document_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="doc_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>国际名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['code']['international_name'];?>" name="international_name" id="doc_title" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
              <dl class="row">
        <dt class="tit">
          <label>中文名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['code']['chinese_name'];?>" name="chinese_name" id="doc_title" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
              <dl class="row">
        <dt class="tit">
          <label>国际代码</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo trim($output['code']['international_code']);?>" name="international_code" id="doc_title" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
              <dl class="row">
        <dt class="tit">
          <label>国际简码</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['code']['international_brief'];?>" name="international_brief" id="doc_title" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
              <dl class="row">
        <dt class="tit">
          <label>洲际</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['code']['continent'];?>" name="continent" id="doc_title" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a> </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#doc_form").valid()){
     $("#doc_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#doc_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            doc_title : {
                required   : true
            },
			doc_content : {
                required   : true
            }
        },
        messages : {
            doc_title : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['document_index_title_null'];?>'
            },
			doc_content : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['document_index_content_null'];?>'
            }
        }
    });
});

function insert_editor(file_path){
	KE.appendHtml('doc_content', '<img src="'+ file_path + '" alt="'+ file_path + '">');
}
function del_file_upload(file_id)
{
    if(!window.confirm('<?php echo $lang['nc_ensure_del'];?>')){
        return;
    }
    $.getJSON('index.php?act=document&op=ajax&branch=del_file_upload&file_id=' + file_id, function(result){
        if(result){
            $('#' + file_id).remove();
        }else{
            alert('<?php echo $lang['document_index_del_fail'];?>');
        }
    });
}
</script>