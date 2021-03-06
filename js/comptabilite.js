"use strict";

var queryString = {};
window.location.href.replace(
    new RegExp("([^?=&]+)(=([^&]*))?", "g"),
    function($0, $1, $2, $3) { queryString[$1] = $3; }
);

if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
	$(window).load(function(){
    $('input:-webkit-autofill').each(function(){
        var text = $(this).val();
        var name = $(this).attr('name');
        $(this).after(this.outerHTML).remove();
        $('input[name=' + name + ']').val(text);
    });
});}

$(function(){
	$('.menu_link').click(function() {
		ajax_page('#content', '<p style="text-align: center">Chargement ...</p>', this.href);
		return false;
	});

	var section = queryString['section'];
	if(section === undefined){
		ajax_page('#content', '<p style="text-align: center">Chargement ...</p>', "ct-documents.php?section=estimation");
	}else{
		ajax_page('#content', '<p style="text-align: center">Chargement ...</p>', "ct-documents.php?section="+section);
	}
});

/* Fonction de chargement ajax simple */
function ajax_page(ele, msg, url) {
	$(ele).html(msg).load(url, function() {
		if($("form#options").length>0){
			$('form#options').submit(function() {
        $("#save-options").val("Saving...");
				$.ajax({
					type: "GET",
					url: "ct-operations.php",
					data: $(this).serialize(),
					dataType: "json",
					success: function(msg) {
							$("#save-options").val("Saved").delay('2000');
							$("#save-options").val("Save");
							return false;
						}
				});
				return false;
			});
		}
		$("#bulk_action_button").click(function(){
			var loader = $('<div>',{
				class:'loading'
			});

			if($("#documents_number").length > 0){
				$("#documents_number").after(loader);
			}

			$.ajax({
				type:"GET",
				url:this.href,
				data:$("#bulk_action").serialize(),
				dataType:'json',
				success: function(data){
					var msg = data.msg;
					if($("#documents_number").length < 1){
						var span = $('<span>', {
							text: msg,
							id: "documents_number"
						});
						$("#bulk_action_button").after(span);
					}
					else{
						$("#documents_number").html(msg);
					}
					$(".loading").remove();
					window.open(data.filename,"_self");
				}
			});
			return false;
		});
		$( ".date_picker" ).datepicker({ dateFormat: "yy-mm-dd", onSelect:function(){
		$.ajax({
			type:"GET",
			url:"ct-bulk.php",
			data:"operation=number_of_docs&" + $("#bulk_action").serialize(),
			dataType:'json',
			success: function(data){
				var msg = " "+ data.msg + " documents";
				if($("#documents_number").length < 1){
					var span = $('<span>', {
						text: msg,
						id: "documents_number"
					});
					$("#bulk_action_button").after(span);
				}
				else{
					$("#documents_number").html(msg);
				}
			}
		});
		}
		});
		$("#date_end").datepicker("setDate" , new Date() );
		// Remove document
		$('.delete_document').click(function() {
			var datas = "operation=delete_document&datas=" + $(this).attr("id");

			$.ajax({
				type: "GET",
				url: "ct-operations.php",
				data: datas,
				dataType: "json",
				success: function(msg) {
					ajax_page('#content', '<p style="text-align: center">Chargement ...</p>', 'ct-documents.php?section=' + msg.redirect);
					return false;
				}
			});
		});
	});
}