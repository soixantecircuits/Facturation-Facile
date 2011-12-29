$(document).ready(function()
				  {
				  	
				  $('.menu_link').click(function ()
										 {
										  ajax_page('#content','<p style="text-align: center">Chargement ...</p>',this.href);
										  return false;
										 });
				  // New document
				  /*$('.new_document').click(function () {
									var datas = "operation=new_document&datas=" + $(this).attr("id");
										   
									$.ajax({
										type: "GET",
										url: "ct-operations.php",
										data: datas,
										dataType: "php",
										success: function(msg) {
											ajax_page('#content','<p style="text-align: center">Chargement ...</p>', 'ct-document.php?section=' + msg);
											return false;
										}
									});
									});*/
								 
				  // Remove document
				  $('.delete_document').click(function () {
									var datas = "operation=delete_document&datas=" + $(this).attr("id");
										   
									$.ajax({
										type: "GET",
										url: "ct-operations.php",
										data: datas,
										dataType: "php",
										success: function(msg) {
											ajax_page('#content','<p style="text-align: center">Chargement ...</p>', 'ct-documents.php?section=' + msg);
											return false;
										}
									});
									});
									
				  // Copy document
				  /*$('.copy_document').click(function () {
									var datas = "operation=copy_document&datas=" + $(this).attr("id");
										   
									$.ajax({
										type: "GET",
										url: "ct-operations.php",
										data: datas,
										dataType: "php",
										success: function(msg) {
											ajax_page('#content','<p style="text-align: center">Chargement ...</p>', 'ct-documents.php?section=' + msg);
											return false;
										}
									});
									});*/
					 
										 
				  }
				  
				  				  
				  )

/* Fonction de chargement ajax simple */
function ajax_page(ele,msg,url){
	$(ele).html(msg).load(url);
}