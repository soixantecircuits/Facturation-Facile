$(document).ready(function()
				  {
				  
				  //jQuery('a[rel*=facebox]').facebox();
				  
				  $('.operation').click(function ()
										{
										ajax_operation('#content','<p style="text-align: center">Opération en cours ...</p>',this.href);
										return false;
										});
				  
				  /*$('.open_document').click(function ()
										{
										ajax_new_document('#content','<p style="text-align: center">Opération en cours ...</p>',this.href);
										return false;
										});*/
				  }
)

/* Fonction de chargement ajax simple */
function ajax_operation(ele,msg,url){
	
	$(ele).html(msg).load(url, "", function (responseText, textStatus, XMLHttpRequest) {
						  $(ele).load("ct-documents.php?section="+responseText);
						  }
						  );
}