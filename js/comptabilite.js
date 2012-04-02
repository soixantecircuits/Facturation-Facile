$(function(){

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