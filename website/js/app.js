$(function() {
	fitWindow();

	// On passe les icones du noir au blanc et inversement lors du survol
	$("#contact_list ul li").hover(function() {
		$("i", this).addClass("icon-white").removeClass("icon-black");	
	}, function() {
		$("i", this).removeClass("icon-white").addClass("icon-black");
	});

	$("#discussion_wrap ul li").click(function() {
		setActiveTab($(this).attr("id"));
	});

	loadContact();
});

function loadContact() {
	$.ajax({
	  url: "client/app/php/contact_list.php"
	}).done(function ( data ) {
	  $("#contacts_div").html(data);
	});
}

window.onresize = fitWindow;
function fitWindow() {
	var body = $("body");
	$("#left_container").css("height", (body.height() - 50)+"px");
	$("#contact_list").css("height", ($("#left_container").height() - $("#videoBox").height())+"px");

	$("#discussion_wrap").css("height", (body.height() - 50)+"px")				
						 .css("width", (body.width() - $("#contact_list").width() - 0)+"px");
						 														//  |-> Padding of div#contact_list
}

function addTab(name, id, removable) {
	var remove = "";
	if(removable) {
		remove = "<a onclick='removeTab(\""+id+"\")'><i class='icon-remove'></i></a>";
	}

	if($("#discussion_wrap ul #"+id).length != 0) {
		setActiveTab(id);

		return;
	}

	var div = $("<div id='"+id+"-div'></div>");
	var li = $("<li id='"+id+"' onclick='setActiveTab(\""+id+"\");'>"+name+" "+remove+"</li>");

	$("#discussion_wrap ul").append(li);
	$("#tab_contents").append(div);
	setActiveTab(id);

	return $("#tab_contents #"+id+"-div");
}
function setActiveTab(id) {
	$("#tab_contents div").hide();
	$("#tab_contents #"+id+"-div").show();

	$("#discussion_wrap ul li").removeClass("selected");
	$("#discussion_wrap ul #"+id).addClass("selected");
}
function removeTab(id) {
	$("#tab_contents #"+id+"-div").remove();
	$("#discussion_wrap ul #"+id).remove();
}