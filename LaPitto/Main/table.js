var xmlHttp
var line
var str
var LOGIN_PAGE = "http://120.24.232.140/LaPitto/"



function load(){
	$("span#admin").hide()
	$("span#normal").hide()
	var cur = "unkown"
	/*$.post("load.php",{},function(data,status){
		cur=data
		})
		
		alert("loading")
	$("div.title").html("校长办公室督办系统 - 当前用户："+cur)*/
	
	$.ajax({
		type: "POST",
		url: "load.php",
		cache:false,
		async:false,
		success: function(xmlobj){
			cur = xmlobj
			$("div.title").replaceWith("<div class='title navbar-text navbar-right' style='margin-right:0px;padding-left:5px;padding-right:5px'>校长办公室督办系统 - 当前用户："+xmlobj+"</div>" )
		}
	});
	
	showYearList()
	showEventList()
	showLeaderList()
	showResponsibility()
	showAssistantList()
	showContentList()
	$("input[name='level'][value='2']").attr('checked','true');
	if(cur=="管理员"){
		$("span.admin").show()
		$("span.normal").hide()
	}
	else if(cur!="观察者"&&cur!=""){
		$("span.admin").hide()
		$("span.normal").show()
	}
	else{
		$("span.admin").hide()
		$("span.normal").hide()
	}
	relo();
	$(".lv-meeting").show();
	$(".panel-heading").show();
	$(".content-table").hide();
}
function relo(){
	var tb = document.getElementById("MainTable")
	str = "<tr>\
        	<td colspan=\"12\"><div class='pull-left' style=\"margin:5px\">校长办公会</div>\
        	<span class='admin'>\
          		<button type=\"button\" class=\"btn btn-info pull-right\" data-toggle=\"modal\" data-target=\"#myModal\">添加内容</button>\
        	</span>\
        	<div class='clearfix'></div>\
        	</td>\
        </tr>" 
	line = "undefined"

	var sel_year = $("select#year_a").find("option:selected").text()
	var sel_level = $("input[name='level']:checked").val()
	var status = $("#statusList").find("option:selected").text()
	var self_status = $("#selfStatusList").find("option:selected").text()

	$.ajax({
		type: "POST",
		url: "reloadTable.php",
		cache:false,
		async:false,
		data:"level="+sel_level+"&year="+sel_year+"&status="+status+"&self_status"+self_status, 
		success: function(xmlobj){
			line = xmlobj
		}
	});

	tb.innerHTML = str+line
}

function showYearList(){
	var sel = $("select.yearList")
	var date = new Date()
	var years = date.getFullYear()
	for(var i=2014;i<years+5;i++){
		
		if(i == 2014){
			sel.append("<option selected='selected'>"+i+"</option>")
		}else {
			sel.append("<option>"+i+"</option>")
		}
	}
}

function showLeaderList(){
	var ac = $("#ac_leader")
	$.post("getLeader.php",
	{
	},
	function(data,status){
		ac.append(data)
	})
	var rc = $("#rc_leader")
	$.post("getLeader.php",
	{
	},
	function(data,status){
		rc.append(data)
	})
}

function showAssistantList(){
	var ac = $("#ac_assistant")
	$.post("getAssistant.php",{},
	function(data,status){
		ac.append(data)
	})
	var rc = $("#rc_assistant")
	$.post("getAssistant.php",{},
	function(data,status){
		rc.append(data)
	})
}

function showResponsibility(){
	var ac = $("#ac_responsibility")
	$.post("getResponsibility.php",
	{
	},
	function(data,status){
		ac.append(data)
	})
	var rc = $("#rc_responsibility")
	$.post("getResponsibility.php",
	{
	},
	function(data,status){
		rc.append(data)
	})
}

function showContentList(){
	var sel = $("select#sx_index")
	$.post("getContent.php",{},
	function(data,status){
		sel.append(data)
	})
}
function showEventList(){
	var sel = $("select#ac_event")
	var lc = $("select#lc_event")
	
	$.post("getEvent.php",
  	{
  	},
  	function(data,status){
    	sel.append(data)
		lc.append(data)
  	})
	
}

/*function addContent(){
	$.post("newContent.php",{
		ac_year:$("select#ac_year").find("option:selected").text(),
		ac_meeting:$("input#ac_meeting").val(),
		ac_1:$("input#ac_1").val(),
		ac_3:$("input#ac_3").val(),
		ac_4:$("select#ac_leader").find("option:selected").text(),
		ac_5:$("select#ac_responsibility").find("option:selected").text(),
		ac_6:$("select#ac_assistant").find("option:selected").text(),
		ac_7:$("select#ac_7").find("option:selected").text(),
		ac_8:$("input#ac_8").val(),
		ac_9:$("input#ac_9").val(),
		ac_10:$("input#ac_10").val(),
		ac_11:$("select#ac_11").find("option:selected").text(),
	},
	function(data,status){
		alert(data)
	});
	
}*/
/*
function addContent(){
	var sel_assistant = $("select#ac_assistant").find("option:selected").text()
	alert(sel_assistant)
}*/
/*
function addMeeting(){
	var ret
	am_year = $("select#year_a").find("option:selected").text()
	am_index = $("input#am_index").val()
	am_content = $("input#am_content").val()
	$.post("newMeeting.php",
  	{
    	year:am_year,
		index:am_index,
		content:am_content
  	},
  	function(data,status){
    	ret = data
		alert(ret)
  	});
	showEventList()
}*/

function addUser(){
	var ret
	au_name = $("input#au_name").val()
	au_pw = $("input#au_pw").val()
	au_cn = $("input#au_cn").val()
	au_weight = $("input#au_weight").val()
	au_usage = $("select#au_usage").find("option:selected").text()
	$.post("newUser.php",
  	{
    	name:au_name,
		pw:au_pw,
		cn:au_cn,
		usage:au_usage,
		weight:au_weight
  	},
  	function(data,status){
    	ret = data
		alert(ret)
  	});
	showEventList()
}

function sx_confirm(){
	var sx_index = $("select#sx_index").find("option:selected").text()
	var sx_comment = $("input#sx_comment").val()
	var sx_program = $("input#sx_program").val()
	var sx_self = $("select#sx_self").find("option:selected").text()
	$.post("redraftContent.php",
	{
		index:sx_index,
		comment:sx_comment,
		program:sx_program,
		self:sx_self
	},
	function(data,status){
		alert(data)
	});
}

function lockEvent(tof){
	var lc_event = $("select#lc_event").find("option:selected").text()
	$.post("lockEvent.php",{
		content: lc_event,
		lock:tof
	},
	function(data,status){
		alert(data)
	});

}

$(document).ready(function(){
	$.post('checkAuth.php',function(data){
		if(data == 1){
			window.location = LOGIN_PAGE;
		}
	});

	$("input[name='level']").on('click',function(){
		var sel_level = $("input[name='level']:checked").val()
		if (sel_level == 1){
			$(".lv-meeting").show();
			$(".panel-heading").hide();
			$(".content-table").hide();
		}
		if (sel_level == 2){
			$(".lv-meeting").show();
			$(".panel-heading").show();
			$(".content-table").hide();
		}
		if (sel_level == 3){
			$(".lv-meeting").show();
			$(".panel-heading").show();
			$(".content-table").show();
		}
	});

	$("select#statusList").on("change",function(){
		var status = $(this).find("option:selected").text();
		if(status != "全选"){
			$(".vc_7[data-content!='"+status+"']").parentsUntil("tbody").hide();
			$(".vc_7[data-content='"+status+"']").parentsUntil("tbody").show();
		} else{
			location.reload();
		}
	})

	$("select#selfStatusList").on("change",function(){
		var self_status = $(this).find("option:selected").text();
		if(self_status != "全选"){
			$(".self_status[data-content!='"+self_status+"']").parentsUntil("tbody").hide();
			$(".self_status[data-content='"+self_status+"']").parentsUntil("tbody").show();
		} else{
			location.reload();
		}
	})

	$(document).on('click','.lv-meeting',function(){
		var event_bar = $(this).siblings('.lv-event');
		event_bar.toggle();
	});

	$(document).on('click',".panel-heading",function(){
		$(this).siblings('.content-table').toggle();
	});
	
	$('#logout').on('click',function(){
		$.post('destroySession.php',function(data){
			window.location = LOGIN_PAGE;
		})
	});

	$("#year_a").on("click",{},function(){
		relo();
		$.post('checkAuth.php',function(){
			$("input[name='level'][value='1']").attr('checked',false);
			$("input[name='level'][value='2']").attr('checked','true');
			$("input[name='level'][value='3']").attr('checked',false);
		})
		$(".lv-meeting").show();
		$(".panel-heading").show();
		$(".content-table").hide();
	});

	$(document).on("click",".btn.btn-danger.multiselect",function(){
		$(this).attr("class","btn btn-success multiselect");
	});

	$(document).on("click",".btn.btn-success.multiselect",function(){
		$(this).attr("class","btn btn-danger multiselect");
	});

	$(document).on("click",".lockComment",function(){
		var str = new String();
		var arr = new Array();
		str = $(this).siblings(".cid").text();
		arr = str.split('：');
		cid = arr[1];
		$.post("lock.php",{index:cid,type:0},function(){
			relo();
		});																//做法就是 先改数据库 然后重载网页 其实是有更好的做法的 就是之前说过的json方法 把所有的应该写在html里的都写到html里面去而不是echo出来 时间原因吧。。 
	});
	$(document).on("click",".lockProgram",function(){
		var str = new String();
		var arr = new Array();
		str = $(this).siblings(".cid").text();
		arr = str.split('：');
		cid = arr[1];
		$.post("lock.php",{index:cid,type:1},function(){
			relo();
		});
	});
	$(document).on("click",".lockSelf",function(){
		var str = new String();
		var arr = new Array();
		str = $(this).siblings(".cid").text();
		arr = str.split('：');
		cid = arr[1];
		$.post("lock.php",{index:cid,type:2},function(){
			relo();
		});
	});

	// $(document).on('click','.modify',function(){
	// 	var self_status = $(this).siblings().find("select.cc_11").find("option:selected").text();
	// 	var comment_a = $(this).siblings().find('textarea.comment_a').val();
	// 	var program = $(this).siblings().find('textarea.program').val();
	// 	var meeting = $(this).attr("data-cid");
	// 	var year = $("select#year_a").find("option:selected").text();
	// 	var comment_b = $(this).siblings().find('div.vc_8').text();
	// 	var opinion_a = $(this).siblings().find('div.vc_3').text();
	// 	var reason = $(this).siblings().find('div.vc_1').text();
	// 	var leader = $(this).siblings().find('div.vc_leader').text();
	// 	var responsibility = $(this).siblings().find('div.vc_responsibility').text();
	// 	var assistant = $(this).siblings().find('div.vc_assistant').text();
	// 	var status = $(this).siblings().find('div.vc_7').text();
	// 	$.post("newContent.php",{
	// 		"year":year,
	// 		"meeting":meeting,
	// 		"reason":reason,
	// 		"opinion_a":opinion_a,
	// 		"comment_b":comment_b,
	// 		"comment_a":comment_a,
	// 		"program":program,
	// 		"leader":leader,
	// 		"responsibility":responsibility,
	// 		"assistant":assistant,
	// 		"status":status,
	// 		"self_status":self_status
	// 	},function(status){
	// 		relo();
	// 	});
	// })

	$(document).on('click',"#ac_button",function(){
		var modal = $("#myModal");
		var year = modal.find("#ac_year").find("option:selected").text();
		var meeting = modal.find("#ac_meeting").val();
		var reason = modal.find("#ac_1").val();
		var opinion_a = modal.find("#ac_3").val();
		var comment_b = modal.find("#ac_8").val();
		var comment_a = modal.find("#ac_9").val();
		var program = modal.find("#ac_10").val();
		var leader = modal.find("#ac_leader").find("option:selected").text();
		var responsibility = modal.find("#ac_responsibility").find("option:selected").text();
		var assistant = modal.find("#ac_assistant").find("option:selected").text();
		var status = modal.find("#ac_7").find("option:selected").text();
		var self_status = modal.find("#ac_11").find("option:selected").text();
		$.post("newContent.php",{
			"year":year,
			"meeting":meeting,
			"reason":reason,
			"opinion_a":opinion_a,
			"comment_b":comment_b,
			"comment_a":comment_a,
			"program":program,
			"leader":leader,
			"responsibility":responsibility,
			"assistant":assistant,
			"status":status,
			"self_status":self_status
		},function(status){
			relo();
		});
		$("#myModal").modal('hide');
	});

	$(document).on('click',"#rc_button",function(){
		var modal = $("#modifyModal");
		var index = modal.find(".modal-title").text();
		var opinion_a = modal.find("#rc_3").val();
		var comment_b = modal.find("#rc_8").val();
		var comment_a = modal.find("#rc_9").val();
		var program = modal.find("#rc_10").val();
		var leader = modal.find("#rc_leader").find(".btn-success").text();
		var responsibility = modal.find("#rc_responsibility").find(".btn-success").text();
		var assistant = modal.find("#rc_assistant").find(".btn-success").text();
		var status = modal.find("#rc_7").find("option:selected").text();
		var self_status = modal.find("#rc_11").find("option:selected").text();
		var leader;
		var responsibility;
		var assistant;
		$.post("redraftContent.php",{
			"index":index,
			"opinion_a":opinion_a,
			"comment_b":comment_b,
			"comment_a":comment_a,
			"program":program,
			"leader":leader,
			"responsibility":responsibility,
			"assistant":assistant,
			"status":status,
			"self_status":self_status
		},function(status){
			relo();
		});
		$("#modifyModal").modal('hide');
	});

	$("#modifyModal").on('show.bs.modal',function(event){
		var button = $(event.relatedTarget);
		var cid = button.data('cid');
		var modal = $(this);
		var count;
		var is_super;
		$.post("load.php",function(user){
			if(user == "管理员"){
				is_super = 1;
			} else{
				is_super = 0;
				$(".super").hide();
				if($(".self.lock").length > 0){
					$(".modify.self").hide();
				}
				if($(".comment.lock").length > 0){
					$(".modify.comment").hide();
				}
				if($(".program.lock").length > 0){
					$(".modify.program").hide();
				}
			}
		});


		count = $("#rc_7 option").length;
		for( var i = 0; i < count; i++){
			$("#rc_7").get(0).options[i].selected = false;
		}
		count = $("#rc_11 option").length;
		for( var i = 0; i < count; i++){
			$("#rc_11").get(0).options[i].selected = false;
		}
		modal.find('.modal-title').text(cid);

		$("#modifyModal").find("button[data-name]").attr("class","btn btn-danger multiselect");

		$.post("getFixedTable.php",{index:cid},function(data){
			$("#rc_3").val(data[0]);
			
			$.each(data[1],function(key,value){
				$("#rc_leader").find("button[data-name='"+value+"']").attr("class","btn btn-success multiselect");
			});
			
			$.each(data[2],function(key,value){
				$("#rc_responsibility").find("button[data-name='"+value+"']").attr("class","btn btn-success multiselect");
			});
			$.each(data[3],function(key,value){
				$("#rc_assistant").find("button[data-name='"+value+"']").attr("class","btn btn-success multiselect");
			});
			count = $("#rc_7 option").length;
			value = data[4];
			for( var i = 0; i < count; i++){
				if($("#rc_7").get(0).options[i].text == value){
					$("#rc_7").get(0).options[i].selected = true;
					break;
				}
			}
			$("#rc_8").val(data[5]);
			$("#rc_9").val(data[6]);
			$("#rc_10").val(data[7]);
			count = $("#rc_11 option").length;
			value = data[8];
			for( var i = 0; i < count; i++){
				if($("#rc_11").get(0).options[i].text == value){
					$("#rc_11").get(0).options[i].selected = true;
					break;
				}
			}
		},'json');
	});
	$("#exportModal").on('show.bs.modal',function(event){
		var sel_year = $("select#year_a").find("option:selected").text();
		var modal = $(this);
		$.post("export.php",{year:sel_year},function(data){
			modal.find("#exportContent").val(data);
		})
	});
	$("#importButton").on("click",function(){
		var content = $("#importContent").val();
		$("#importModal").modal('hide');
		array = content.split("\n");
		var count = array.length;
		for(var i = 0 ; i < count; i++){
			$.post("import.php",{data:array[i]},function(info){

			})
		}
	})
})
