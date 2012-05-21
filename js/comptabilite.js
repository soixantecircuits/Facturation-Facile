$(function(){
	$( ".date_picker" ).datepicker();

	$('.menu_link').click(function() {
		ajax_page('#content', '<p style="text-align: center">Chargement ...</p>', this.href);
		return false;
	});

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