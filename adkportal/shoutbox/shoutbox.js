/**
 * Adk Portal
 * Version: 3.0
 * Official support: http://www.smfpersonal.net
 * Author: Adk Team
 * Copyright: 2009 - 2014 © SMFPersonal
 * Developers:
 * 		Juarez, Lucas Javier
 * 		Clavijo, Pablo
 *
 * version smf 2.0*
 */

$(document).ready(function(){
	var inputUser = $("#nick");
	var inputMessage = $("#message_shoutbox");
	var loading = $("#loading");
	var messageList = $(".contente");
	
	function updateShoutbox(){
		messageList.hide();
		loading.fadeIn();
		$.ajax({
			type: "POST", url: smf_scripturl, data: "action=shoutboxAjax&sa=update",
			complete: function(data){
				loading.fadeOut();
				messageList.html(data.responseText);
				messageList.fadeIn(2000);
			}
		});
	}
	function checkForm(){
		if(inputUser.attr("value") && inputMessage.attr("value"))
			return true;
		else
			return false;
	}
	
	updateShoutbox();
	
	$("#form").submit(function(){
		if(checkForm()){
			var nick = inputUser.attr("value");
			var message = inputMessage.attr("value");
			var id_user = inputMessage.attr("id_user");

			$("#send").attr({ disabled:true, value:smf_shoutbox_text_sending });
			$("#send").blur();
			$.ajax({
				type: "POST", url: smf_scripturl, data: "action=shoutboxAjax&sa=insert&nick=" + nick + "&message=" + message,
				complete: function(data){
					messageList.html(data.responseText);
					updateShoutbox();
					$("#send").attr({ disabled:false, value:smf_shoutbox_shout_it });
				}
			 });
			
			document.getElementById("message_shoutbox").value = "";
		}
		else alert(smf_shoutbox_fill);
		return false;
	});
});

function addSmiley(smiley)
{
	replaceText(smiley, document.getElementById("message_shoutbox"));
}
	
function OpenShoutbox(id)
{
	if(document.getElementById(id).style.display == "none"){
		document.getElementById(id).style.display = "block";
	}
	else{
		document.getElementById(id).style.display = "none";
	}
}

function addBBCode(id)
{
	surroundText("[" + id + "]", "[/" + id + "]", document.getElementById("message_shoutbox"));
}
function updateShout()
{
	var messageList = $(".contente");
	var loading = $("#loading");
	
	messageList.hide();
	loading.fadeIn();
	$.ajax({
			type: "POST", url: smf_scripturl, data: "action=shoutboxAjax&sa=update",
		complete: function(data){
			loading.fadeOut();
			messageList.html(data.responseText);
			messageList.fadeIn(2000);
		}
	});
}

function finalUpdate()
{
	updateShout();
}