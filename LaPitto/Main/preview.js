$(document).ready(function(){
	$.post('checkAuth.php',function(data){
		if(data == 1){
			window.location = LOGIN_PAGE;
		}
	});

	$(window).unload(function(){
		$.post('destroySession.php',function(data){
			
		})
	})
	var sel = $("select.yearList")
	var date = new Date()
	var years = date.getFullYear()
	for(var i=2001;i<years+1;i++){
		sel.append("<option>"+i+"</option>")
	};

	$("#displayPreview").on("click",function(){
		var display = $("select#display").find("option:selected").text()
		var sel_year = $("select#year_a").find("option:selected").text()
		var tb = document.getElementById("MainTable")
		str = "<tr>\
        	<td colspan=\"12\">会议记录</td>\
        </tr>" 
		line = "undefined"
		
		
		if(display=="按会议排序"){
		$.ajax({
			type: "POST",
			url: "previewByMeeting.php",
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