$(document).ready(function(){
	var LOGIN_PAGE = '120.24.232.140/LaPitto/index.html'
	$.post('checkAuth.php',function(data){
		if(data == 1){
			window.location = LOGIN_PAGE;
		}
	});

	var sel = $("select.yearList")
	var date = new Date()
	var years = date.getFullYear()
	for(var i=2001;i<years+1;i++){
		sel.append("<option>"+i+"</option>")
	};

	$("#displayPreview").on("click",function(){
		var display = $("select#display").find("option:selected").text()
		var sel_year = $("select#year_a").find("option:selected").text()
		var mod = $("select#mod").find("option:selected").attr("value")
		var tb = document.getElementById("MainTable")
		str = "<div class='row show-grid'>\
		<div class='col-lg-1 table-header' style='border:1px solid rgba(86,61,124,.2);background-color:rgb(0,204,255);display:none'>意见编号</div>\
		<div class='col-lg-1 table-header' style='border:1px solid rgba(86,61,124,.2);background-color:rgb(0,204,255);display:none'>牵头领导</div>\
		<div class='col-lg-1 table-header' style='border:1px solid rgba(86,61,124,.2);background-color:rgb(0,204,255);display:none'>牵头单位</div>\
		<div class='col-lg-1 table-header' style='border:1px solid rgba(86,61,124,.2);background-color:rgb(0,204,255);display:none'>协助单位</div>\
		<div class='col-lg-1 table-header' style='border:1px solid rgba(86,61,124,.2);background-color:rgb(0,204,255);display:none'>落实状态</div>\
		<div class='col-lg-5 table-header' style='border:1px solid rgba(86,61,124,.2);background-color:rgb(0,204,255);display:none'>拟办意见</div>\
		<div class='col-lg-2 table-header' style='border:1px solid rgba(86,61,124,.2);background-color:rgb(0,204,255);display:none'>备注</div>\
		<div class='col-lg-8' style='background-color:rgb(0,204,255);margin-top:'>校长会议</div>\
		<div class='col-lg-2 year-completion-real' style='background-color:rgb(0,204,255)'>实际完成率</div>\
		<div class='col-lg-2 year-completion-ref' style='background-color:rgb(0,204,255)'>参考完成率</div>" 
		line = "undefined"

		if(display=="按会议排序"){
		$.ajax({
			type: "POST",
			url: "previewByMeeting.php",
			cache:false,
			async:false,
			data:"year="+sel_year+"&mod="+mod,
			success: function(xmlobj){
				line = xmlobj	
			}
		});
		tb.innerHTML = str+line+"</div>";
		if(mod == 2){
			$(".table-header").show()
		}
			var count = 0
			var real = 0.0
			var ref = 0.0
			$(".cid").each(function(){
				var cindex = $(this).data('cid');
				var status = parseInt($(".status[data-cid='"+cindex+"']").attr('value'))
				real = real + status
				count = count + 1
				if(ref == 100){
					ref = ref + 1
				}
			})
			real = real / count 
			ref = ref / count
			$(".year-completion-real").text($(".year-completion-real").text()+": "+real.toFixed(2)+'%')
			$(".year-completion-ref").text($(".year-completion-ref").text()+": "+ref.toFixed(2)+'%')
			$(".meeting-completion-real").each(function(){
				year = $(this).data('year');
				meeting = $(this).data('meeting');
				real = parseFloat($(".real[data-year='"+year+"'][data-meeting='"+meeting+"']").data('real')).toFixed(2);
				$(this).text($(this).text()+":"+real+"%")
			})
			$(".meeting-completion-ref").each(function(){
				year = $(this).data('year');
				meeting = $(this).data('meeting');
				ref = parseFloat($(".ref[data-year='"+year+"'][data-meeting='"+meeting+"']").data('ref')).toFixed(2);
				$(this).text($(this).text()+":"+ref+"%")
			})
		}
		else{
		$.ajax({
			type: "POST",
			url: "previewByLeader.php",
			cache:false,
			async:false,
			data:"cid="+document.getElementById("cid").checked
			+"&content="+document.getElementById("content").checked
			+"&leader="+document.getElementById("leader").checked
			+"&responsibility="+document.getElementById("responsibility").checked
			+"&assistant="+document.getElementById("assistant").checked
			+"&status="+document.getElementById("status").checked
			+"&comment_b="+document.getElementById("comment_b").checked
			+"&comment_a="+document.getElementById("comment_a").checked
			+"&program="+document.getElementById("program").checked
			+"&self_status="+document.getElementById("self_status").checked
			+"&year="+sel_year,
			success: function(xmlobj){
				line = xmlobj		
			}
		});
			tb.innerHTML = str+line;
		}
	});
});