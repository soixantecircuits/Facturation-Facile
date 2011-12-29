function operations_selection()
{
	var a_venir = $('select[name*="a_venir"] > option:selected').val();
	var compte = $('select[name*="compte_choice"] > option:selected').val();
	var mois = $('select[name*="mois"] > option:selected').val();
	var annee = $('select[name*="annee"] > option:selected').val();

	$.ajax({
	  type: "GET",
	  url: "ct-operations.php",
	  data: "operation=display_operations&compte=" + compte + "&month=" + mois + "&year=" + annee + "&a_venir=" + a_venir,
	  dataType: "php",
	  success: function(msg) {      
	  	display_operations(msg);
	  	refresh_tab();
	  }
	  });
}

function display_operations(xmlstring)
{
	var xmlobject = (new DOMParser()).parseFromString(xmlstring, "text/xml"); 
	$(xmlobject).find('tableau_operations').each(function(){
	
		$("#tab_operations").empty();
	
		$(this).find('operation').each(function(){
			var id = $(this).find('id').text();
			var date_operation = $(this).find('date_operation').text();
			var date_facture = $(this).find('date_facture').text();
			var categorie = $(this).find('categorie').text();
			var provenance = $(this).find('provenance').text();
			var objet = $(this).find('objet').text();
			var compte = $(this).find('compte').text();
			var debit = $(this).find('debit').text();
			var credit = $(this).find('credit').text();
			var credit_tva = $(this).find('credit_tva').text();
			var debit_tva = $(this).find('debit_tva').text();
			var remarques = $(this).find('remarques').text();
			
			var tab = '<tr id="' + id + '">';
			tab += '<td class="date_operation editable_date">' + date_operation + '</td>';
			tab += '<td class="date_facture editable_date">' + date_facture + '</td>';
			tab += '<td class="categorie editable">' + categorie + '</td>';
			tab += '<td class="provenance editable">' + provenance + '</td>';
			tab += '<td class="objet editable">' + objet + '</td>';
			tab += '<td class="compte editable">' + compte + '</td>';
			tab += '<td class="debit editable">' + debit + '</td>';
			tab += '<td class="credit editable">' + credit + '</td>';
			tab += '<td class="credit_tva editable">' + credit_tva + '</td>';
			tab += '<td class="debit_tva editable">' + debit_tva + '</td>';
			tab += '<td class="remarques editable">' + remarques + '</td>';
			tab += '<td><input class="button delete_operation" type=submit value="Delete"></td>';
			tab += '</tr>';

			$("#tab_operations").append(tab);
		});
	});
}

function refresh_total()
{
    $.ajax({
        type: "POST",
        url:  "ct-operations.php?operation=refresh_total",
        success: function(msg) {
        
            var totaux = msg.split("|");
            
            $('#total_banque').html(totaux[0]);
            $('#total_caisse').html(totaux[1]);
        }});
}

function save_operation(operationId, operationType, operationValue)
{
    var datas = {type: operationType, value: operationValue};

    $.ajax({
      type: "POST",
      url: "ct-operations.php?operation=save_operation&id=" + operationId,
      data: datas,
      success: function(msg) {
      
        refresh_total();
      
        $('#status').fadeIn(0);
        $('#status').text("SAVED");
        $('#status').fadeOut('slow');
        
      }
      });
}

function save_edited()
{
    $("#edited").each(function() {
    
        var operationId;
        var operationType;
        var operationValue;
        
        operationId = $("#edited").parent().parent().attr("id");

        var operationType = $("#edited").attr("name");
        var operationValue = $("#edited").val();
        
        if ((operationType == "debit") || (operationType == "credit") || (operationType == "debit_tva") || (operationType == "credit_tva"))
        {
            operationValue = operationValue.replace(",", ".");
        }
        
        
        $("#last_edited").attr("id","");
        
        if (operationValue)
        {
            $("#edited").parent().html(operationValue).attr("id", 'last_edited');
        }
        else
        {
            $("#edited").parent().empty().attr("id", 'last_edited');
        }

        save_operation(operationId, operationType, operationValue);
    });
}

function focus_element(element)
{
    // If the element is a select menu
    if (element.hasClass("categorie") || element.hasClass("compte"))
    {
        // retrieve the class
        if (element.hasClass("categorie"))
            var type = "categorie";
        else
            var type = "compte";
        
        var selectedType = element.html();
        var edit_input = $("#"+ type +"_new").parent().html();
        element.html(edit_input);
        element.children(".new_operation").attr("id", 'edited').attr("class", 'categorie');
        if (type == "categorie")
            $("#edited").val(selectedType);
        else
            $("#edited").val(selectedType).width("60px");
                    
        // If selection change, save the new value
        $("select").change(function () {
            if (element.attr("id") == "edited")
            {                        
                //save_operation($("#edited").parent().parent().attr("id"), $("#edited").attr("name"), $("#edited").val());
                save_edited();
                $("#edited").parent().html($("#edited").val());
            }
        })
    }
    else if (element.hasClass("provenance") || element.hasClass("objet") || element.hasClass("debit") || element.hasClass("credit") || element.hasClass("credit_tva") || element.hasClass("debit_tva") || element.hasClass("remarques")) // if the doubleclicked element is an input
    {
        
        var edit_input = $('<input id="edited" type="text" name="' + element.attr("class").split(' ').slice(0, 1) + '" value="' + element.html() + '"/>');
        edit_input.width(element.width()).height(element.height());
        element.html(edit_input);
        $("#edited").select();
    }
}

function refresh_tab()
{
    // if click anywhere on the page
	$(document).click(function() {
        save_edited();
	});    

    // double click on an element 
	$(".editable").bind("dblclick", function() {
		
        // First save edited input if there is any
        save_edited();
        
        // Focus on the element to modify it
        focus_element($(this));		
	});
	
	$(".editable_date").datePicker({
		startDate:'2008-04-01',
		createButton:false})
	.bind(
		'dblclick',
		function()
		{
			$(this).dpDisplay();
			this.blur();
			return false;
		}
	)
	.bind(
		'dateSelected',
		function(e, selectedDate, $td)
		{
			var month = selectedDate.getUTCMonth() + 1;
			var day = selectedDate.getDate();
			
			if (month < 10)
				month = "0" + month;
				
			if (day < 10)
				day = "0" + day;
            
            if ($(this).hasClass("date_operation"))
            {
                save_operation($(this).parent().attr("id"), "date_operation", selectedDate.getFullYear() + '-' + month + '-' + day);
            }
            else
            {
                save_operation($(this).parent().attr("id"), "date_facture", selectedDate.getFullYear() + '-' + month + '-' + day);
            }
			$(this).html(selectedDate.getFullYear() + '-' + month + '-' + day);
		}
	);

	
	$('.delete_operation').click(function () { 
	
		var id = $(this).parent().parent().attr("id");
		
		if (confirm('Delete ?'))
		{
			var datas = "operation=delete_operation";
			datas += "&id=" + id;
			
			$.ajax({
			type: "GET",
			url: "ct-operations.php",
			data: datas, 
			dataType: "php",
			success: function(msg) {

                refresh_total();

                $('#status').fadeIn(0);
				$('#status').text("DELETED");
                $('#status').fadeOut('slow');
					
				operations_selection();
			}
			});
		}
	});
}

$(document).ready(function(){
	operations_selection();
	
	Date.format = 'yyyy-mm-dd';
	
	$('#date_operation_new').datePicker({
		startDate:'2008-04-01',
		createButton:false,
		clickInput:true});

    
    $('#date_facture_new').datePicker({
		startDate:'2008-04-01',
		createButton:false,
		clickInput:true});
		
	$('#ajouter_operation').click(function () { 
		
		var datas = "operation=add_operation";
		datas += "&date_operation=" + $('input[name*="date_operation"]').val();
		datas += "&date_operation=" + $('input[name*="date_operation"]').val();
		datas += "&date_facture=" + $('input[name*="date_facture"]').val();
		datas += "&categorie=" + $('select[name*="categorie"]').val();
		datas += "&provenance=" + $('input[name*="provenance"]').val();
		datas += "&objet=" + $('input[name*="objet"]').val();
		datas += "&compte=" + $('select[name*="compte"]').val();
		datas += "&debit=" + $('input[name*="debit"]').val();
		datas += "&credit=" + $('input[name*="credit"]').val();
		datas += "&credit_tva=" + $('input[name*="credit_tva"]').val();
		datas += "&debit_tva=" + $('input[name*="debit_tva"]').val();
		datas += "&remarques=" + $('input[name*="remarques"]').val();
		
		$.ajax({
			type: "GET",
			url: "ct-operations.php",
			data: datas, 
			dataType: "php",
			success: function(msg) {
                
                refresh_total();
            
                $('#status').fadeIn(0);
				$('#status').text("ADDED");
                $('#status').fadeOut('slow');
					
				operations_selection();
			}
		});
	});
    
    $('.new_operation').click(function () {
        $("#last_edited").attr("id","");
    });
    
    $(document).keypress(function(e)
	{
        switch(e.keyCode)
		{
            case 13: // Key Enter : save current edited input
                save_edited();
            break;
            case 9: // Key Tab : save current edited input and focus on next element
                
                save_edited();
                $("#last_edited").each(function() {
                    e.preventDefault();
                    focus_element($(this).next());
                });
                
            break;
        }
    });
});