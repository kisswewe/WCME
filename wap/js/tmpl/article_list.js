//33hao v5
$(function(){
	var ac_id = getQueryString('ac_id')
	
	if (ac_id=='') {
    	window.location.href = WapSiteUrl + '/index.html';
    	return;
	}
	else {
		$.ajax({
			url:ApiUrl+"/index.php?act=article&op=article_list",
			type:'get',
			data:{ac_id:ac_id},
			jsonp:'callback',
			dataType:'jsonp',
			success:function(result){
				var data = result.datas;
				$("#art_name ,#art_title").html(data.article_type_name);
				data.WapSiteUrl = WapSiteUrl;
				var html = template.render('article-list', data);				
				$("#article-content").html(html);
			}
		});
	}	
});