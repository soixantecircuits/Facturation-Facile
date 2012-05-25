"use strict";

var clients = {};
var color = 255;
var loadFinished = false;
var TabJour = new Array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
var TabMois = new Array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "décembre");

function modified(){
    if(loadFinished){
        color -= 1;
        $("body").animate({ backgroundColor:"rgb("+color+","+color+","+color+")"}, "slow");
        $('#status').text('MODIFIED');
    }
}

function Date_toYMD(d) {
    var year, month, day;
    year = String(d.getFullYear());
    month = String(d.getMonth() + 1);
    if (month.length == 1) {
        month = "0" + month;
    }   
    day = String(d.getDate());
    if (day.length == 1) {
        day = "0" + day;
    }
    return year + "-" + month + "-" + day;
}

function sqlToDate(d){
    var re = new RegExp("[^\\d]+","g");
    var def_mois = re.exec(d);

    if(def_mois === null){
        var separate_date = d.split("-");
        return separate_date[2]+" "+TabMois[separate_date[1]-1]+" "+separate_date[0];
    }else{
        return d;
    }
}

window.onbeforeunload = function (evt) {
    var message = 'Vous n\'avez pas sauvegardé, êtes-vous sûr de vouloir quitter ?';
    if (typeof evt === 'undefined') {
        evt = window.event;
    }
    if (evt) {
        evt.returnValue = message;
    }
    if($('#status').text() === 'MODIFIED')
        return message;
};

function toSqlDate(the_date){
    var re = new RegExp("[^\\d]+","g");
    var def_mois = re.exec(the_date);

    re = new RegExp('[0-9]+',"g");
    var def_day = re.exec(the_date);

    re = new RegExp('\[0-9]+(?!.*\[0-9])',"g");
    var def_year = re.exec(the_date);


    if(def_mois === null){
        console.log("error _ no date");
    } else {
        for(var i = 0; i < TabMois.length; i++){
            if(TabMois[i].toLowerCase() === def_mois[0].trim().toLowerCase()){
                var date_form = new Date(i+1+"/"+def_day[0]+"/"+def_year[0]);
                return Date_toYMD(date_form);
            }
        }
    }
}

$(document).ready(function() {

    var today = new Date();
    var jour = today.getDay();
    var numero = today.getDate();
    if (numero < 10) numero = "0" + numero;

    var mois = today.getMonth();
    var annee = today.getFullYear();

    var messageDate = numero + " " + TabMois[mois] + " " + annee;

    $('input[name*="number"]').val($('#number').text());
    $('input[name*="tva"]').val(0.196);

    $('#return').attr("href", "index.php?section=" + $('#type').text());

    // Fill fields with xml datas
    var xmlobject = (new DOMParser()).parseFromString(xmlstring, "text/xml");
    $(xmlobject).find('facture').each(function() {
        //var type = $(this).find('type').text();
        var type = $('#type').text();
        var acompte = $(this).find('acompte').text();
        var number = $(this).find('number').text();
        var date = $(this).find('date').text();
        var follower = $(this).find('follower').text();

        if (type == "facture") {
            $('#type').after('(<input type="checkbox" name="acompte">aco.)');
            if (acompte == "true") {
                $('input[name*="acompte"]').attr('checked', "true");
            } else {
                $('#ligne_acompte').hide();
                $('#ligne_net_a_payer').hide();
            }
        } else {
            $('#ligne_acompte').hide();
            $('#ligne_net_a_payer').hide();
            $('#ligne_acompte_verse').hide();
            $('#ligne_montant_reste').hide();
        }

        $('input[name*="date"]').val(sqlToDate(date));
        $('input[name*="follower"]').val(follower);

        var client_name = $(this).find('client').find('name').text();
        var client_contact = $(this).find('client').find('contact').text();
        var client_address = $(this).find('client').find('address').text();
        var client_zip = $(this).find('client').find('zip').text();
        var client_city = $(this).find('client').find('city').text();
        var client_country = $(this).find('client').find('country').text();

        $('input[name*="name"]').val(client_name.replace(/\\/g, ''));
        $('input[name*="contact"]').val(client_contact.replace(/\\/g, ''));
        $('input[name*="address"]').val(client_address.replace(/\\/g, ''));
        $('input[name*="zip"]').val(client_zip.replace(/\\/g, ''));
        $('input[name*="city"]').val(client_city.replace(/\\/g, ''));
        $('input[name*="country"]').val(client_country.replace(/\\/g, ''));

        var resume = "";
        $(this).find('resume').find('resume_line').each(function() {
            resume += $(this).text() + "\n";
        });

        // remove last return in resume string
        resume = resume.slice(0, -1);

        $('textarea[name*="resume"]').val(resume.replace(/\\/g, ''));

        $(this).find('section').each(function() {
            var id;
            id = addSection($(this).find('title').text());

            $(this).find('item').each(function() {
                addLine("#section" + id, $(this).find('description').text().replace(/\\/g, ''), $(this).find('quantity').text(), $(this).find('unit_price').text());
            });
        });

        var remise;

        if ($(this).find('remise').text()) remise = $(this).find('remise').text();
        else remise = 0;
        $('input[name*="remise"]').val(remise);


        var tva;
        if ($(this).find('tva').text()) tva = $(this).find('tva').text();
        else tva = 0.196;
        $('input[name*="tva"]').val(tva);


        var pourc_acompte;
        if ($(this).find('pourc_acompte').text()) pourc_acompte = $(this).find('pourc_acompte').text();
        else pourc_acompte = 0.0;
        $('input[name*="pourc_acompte"]').val(pourc_acompte);


        var acompte_verse;
        if ($(this).find('acompte_verse').text()) acompte_verse = $(this).find('acompte_verse').text();
        else acompte_verse = 0.0;
        $('input[name*="acompte_verse"]').val(acompte_verse);
        //if (!acompte_verse)
        //  $('#ligne_montant_reste').hide();
        var conditions = "";
        $(this).find('conditions').find('conditions_line').each(function() {
            conditions += $(this).text() + "\n";
        });

        // remove last return in resume string
        conditions = conditions.slice(0, -1);

        $('textarea[name*="conditions"]').val(conditions);


        refresh();
        $('#status').text('LOADED');

        $('input').blur(function() {
            refresh();
        });
        loadFinished = true;
    });

    // Today button
    $('#today').click(function() {
        $('input[name*="date"]').val(messageDate);
    });

    // Acompte checkbox
    $('input[name*="acompte"]').click(function() {
        if ($('input[name*="acompte"]').attr("checked")) {
            $('#ligne_acompte').show();
            $('#ligne_net_a_payer').show();
        } else {
            $('#ligne_acompte').hide();
            $('#ligne_net_a_payer').hide();
        }
    });

    // Get document in PDF
    $('#getpdf').click(function() {
        var type = $('#type').text();
        var number = $('#number').text();
        $('#status').text('GETTING PDF ...');
        $.ajax({
            type: "GET",
            url: "ct-operations.php",
            data: "operation=getpdf&type=" + type + "&number=" + number,
            dataType: "json",
            success: function(data) {
                if (data) $('#status').text(data);

                $('#status').text('GET PDF');
                if(data.success)
                    window.open('documents/' + type + '/' + type + number + '.pdf');
                else
                    alert(data.msg);
            }
        });
    });

    // Save document in XML in database
    $('#save').click(function() {
        color = 255;
        $("body").animate({ backgroundColor:"rgb("+color+","+color+","+color+")"}, "slow");

        $('#status').text('Saving...');
        var datas = "operation=save_document";
        datas += "&type=" + $('#type').text();
        datas += "&acompte=" + $('input[name*="acompte"]').attr('checked');
        datas += "&number=" + $('#number').text();
        datas += "&date=" + $('input[name*="date"]').val();
        datas += "&follower=" + encodeURIComponent($('input[name*="follower"]').val());
        datas += "&name=" + encodeURIComponent($('input[name*="name"]').val());
        datas += "&contact=" + encodeURIComponent($('input[name*="contact"]').val());
        datas += "&address=" + encodeURIComponent($('input[name*="address"]').val());
        datas += "&zip=" + $('input[name*="zip"]').val();
        datas += "&city=" + encodeURIComponent($('input[name*="city"]').val());
        datas += "&country=" + encodeURIComponent($('input[name*="country"]').val());
        datas += "&total_ht=" + $('input[name*="total_ht"]').val();
        datas += "&status=" + $('select[name*="current_status"]').val();
        datas += "&date="+ toSqlDate($('input[name*="date"]').val());

        var resume = encodeURIComponent($('textarea[name*="resume"]').val());

        var resume_lines = resume.split("\n");

        datas += "&resume_lines=" + resume_lines.length;

        var resume_line_num = 0;
        while (resume_line_num < resume_lines.length) {
            datas += "&resume_line" + resume_line_num + "=" + resume_lines[resume_line_num];
            resume_line_num += 1;
        }

        datas += "&resume=" + $('textarea[name*="resume"]').val();

        $('#sections').find('.section').each(function() {
            datas += "&" + $(this).find('input').attr("name") + "=" + $(this).find('input').val();

            $(this).find('.line').each(function() {
                if ($(this).find('.description').attr("name")) {
                    datas += "&" + $(this).find('.description').attr("name") + "=" + $(this).find('.description').val();
                    datas += "&" + $(this).find('.quantity').attr("name") + "=" + $(this).find('.quantity').val();
                    datas += "&" + $(this).find('.unit_price').attr("name") + "=" + $(this).find('.unit_price').val();
                }
            });
        });

        if (($('input[name*="remise"]').val() >= 0) && (($('input[name*="remise"]').val() <= 1))) datas += "&remise=" + $('input[name*="remise"]').val();
        else {
            alert("Le document ne peut etre sauvegarde car la remise doit etre comprise entre 0 et 1");
            return 0;
        }

        if ($('input[name*="acompte"]').attr("checked")) {
            if (($('input[name*="pourc_acompte"]').val() >= 0) && (($('input[name*="pourc_acompte"]').val() <= 1))) datas += "&pourc_acompte=" + $('input[name*="pourc_acompte"]').val();
            else {
                alert("Le document ne peut etre sauvegarde car l'acompte doit etre compris entre 0 et 1");
                return 0;
            }
        } else datas += "&pourc_acompte=0";


        if (($('input[name*="acompte_verse"]').val() < parseFloat($('#total_ttc').text())) && ($('input[name*="acompte_verse"]').val() >= 0)) datas += "&acompte_verse=" + $('input[name*="acompte_verse"]').val();
        else {
            alert("Le document ne peut etre sauvegarde car l'acompte verse doit etre inferieur au total ttc");
            return 0;
        }

        var conditions_lines = $('textarea[name*="conditions"]').val().split("\n");

        datas += "&conditions_lines=" + conditions_lines.length;

        var conditions_line_num = 0;
        while (conditions_line_num < conditions_lines.length) {
            datas += "&conditions_line" + conditions_line_num + "=" + conditions_lines[conditions_line_num];
            conditions_line_num += 1;
        }

        $.ajax({
            type: "GET",
            url: "ct-operations.php",
            data: datas,
            dataType: "json",
            success: function(msg) {
                $('#status').text('SAVED');
            }
        });
    });

    $("#sections").sortable({
        update: function(event, ui) {
            modified();
            $("#sections").children().each(function(ind, el) {
                $(this).attr('id', 'section' + ind);
                $(this).find('.addLine').parent().html('<a href="#" class="addLine" onClick="addLine(\'#section' + ind + '\',\'\', 1, $(\'#default_val\').val() ); return false;">[+]</a>');
            });
        }
    });

    $(".section").mouseenter(function() {
        $(this).children(".grip").show();
    }).mouseleave(function() {
        $(this).children(".grip").hide();
    });

    if ($("#conditions").children('textarea').val() === "") {
        $("#conditions").children('textarea').val("Conformément à la Loi °921442 du 31/12/1992 : Le règlement de cette facture doit intervenir au comptant à réception, aucun escompte ne sera appliqué en cas de paiement anticipé. Au-delà d'un délai de trente jours, après la date de facture, il sera appliqué un intérêt de retard égal à une fois et demi le taux de l'intérêt légal, TVA en sus.");
    }

    $("#clients_name").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "ct-operations.php",
                dataType: "json",
                data: {
                    operation: "get_clients",
                    name_contain: request.term
                },
                success: function(data) {
                    response($.map(data.data, function(item) {
                        return {
                            value: item.name,
                            id: item.id,
                            contact: item.contact,
                            address: item.address,
                            zip: item.zip,
                            city: item.city,
                            country: item.country
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            console.log(ui.item ? "Selected: " + ui.item.value + " id " + ui.item.id : "Nothing selected, input was " + this.value);
            $("#contact").val(ui.item.contact);
            $("#address").val(ui.item.address);
            $("#zip").val(ui.item.zip);
            $("#city").val(ui.item.city);
            $("#country").val(ui.item.country);
        }
    });

    $("#update_default_val").click(function(){
        $(".unit_price").val($("#default_val").val());
        refresh();
    });

});

function addSection(title) {
    var id = document.getElementById('id').value;
    document.getElementById('lineid').value = 0;

    var new_section = '<div class="section" id="section' + id + '" > ' + '<a class="removeSection" href="#" onClick="remove($(this).parent()); return false;">[-]</a>' + '<input class="title" type="text" id="section_' + id + '" name="section_' + id + '" value="' + title + '" style="width:98%"/>' + '<table width="98%"> ' + '<tr><td width="57.4%" align=left class="linefirst">D&Eacute;SIGNATION</td> <td width="8.9%" align=right class="linefirst">QT&Eacute;</td> <td width="16.8%" align=right class="linefirst">PRIX UNIT. HT</td> <td width="16.8%" align=right class="linefirst">MONTANT HT</td></tr> ' + '<tr class="lastline"><td align=left class="line"><a href="#" class="addLine" onClick="addLine(\'#section' + id + '\',\'\', 1, $(\'#default_val\').val() ); return false;">[+]</a></td> <td class="line"></td> <td class="line"></td> <td align=right class="line"></td></tr>' + '<tr><td align=left class="line">TOTAL </td> <td class="line"></td> <td class="line"></td> <td align=right class="line"><text id="montant_total">0</text> &euro;</td></tr></table>' + '<span class="grip"></span>' + '</div>';

    $("#sections").append(new_section);
    $("#section" + id).hide();
    $("#section" + id).slideDown("fast");

    var return_id = id;
    id = parseInt(id,10) + 1;
    document.getElementById('id').value = id;

    $(".section").mouseenter(function() {
        $(this).children(".grip").show();
    }).mouseleave(function() {
        $(this).children(".grip").hide();
    });

    return return_id;
}

function remove(id) {
    $(id).slideUp("fast", function() {
        $(id).remove();
        refresh();
    });
}

function addLine(id, description, quantity, unit_price) {
    var lineid = document.getElementById('lineid').value;

    var new_line = '<tr class="line" id="line' + (document.getElementById('id').value - 1) + lineid + '"><td align=right><a class="removeLine" href="#" onClick="remove($(this).parents(\'tr\')); return false;">[-]</a><input class="description" type="text" name="description_' + (document.getElementById('id').value - 1) + '_' + lineid + '" value="' + description + '" style="width:95.8%" /></td><td align=right><input class="quantity" type="text" name="quantity_' + (document.getElementById('id').value - 1) + '_' + lineid + '" value="' + quantity + '" style="width:90%; text-align:right" /></td><td align=left ><input class="unit_price" type="text" name="unitprice_' + (document.getElementById('id').value - 1) + '_' + lineid + '" value="' + unit_price + '" style="width:85%; text-align:right"/><div class="unit">&euro;</div></td><td align=right><text class="montant" >0</text> &euro;</td></tr>';

    $(id).find(".lastline").before(new_line);



    $('#line' + (document.getElementById('id').value - 1) + lineid).find('.unit_price').val(unit_price);

    lineid = parseInt(lineid,10) + 1;
    document.getElementById('lineid').value = lineid;

    $('input[name*="quantity"]').blur(function() {
        refresh();
    });

    $('input[name*="unitprice"]').blur(function() {
        refresh();
    });

     $('input[name*="description"]').blur(function() {
        refresh();
    });

    refresh();

}

function refresh() {

    var quantity = 0;
    var unit_price = 0;
    var montant = 0;
    var montant_total = 0;
    var total = 0;
    var remise = 0;
    var total_ht = 0;
    var total_tva = 0;
    var total_ttc = 0;
    var pourc_acompte = 0;
    var net_a_payer = 0;
    var acompte_verse = 0;
    var montant_reste = 0;

    modified();

    $('.section').each(function() {

        montant_total = 0;

        $(this).find('table > tbody > .line').each(function() {

            quantity = $(this).find('td > input[name*="quantity"]').val();
            unit_price = $(this).find('td > input[name*="unitprice"]').val();

            var montant = quantity * unit_price;

            $(this).find('td > .montant').text(Math.round(montant * 100) / 100);

            montant_total += parseFloat(montant);
            total += parseFloat(montant);

        });

        $(this).find('table > tbody > tr > td > #montant_total').text(montant_total);

    });

    remise = $('#remise > table > tbody > tr').find('td > input[name*="remise"]').val();
    $('#remise > table > tbody > tr').find('td > #remise').text(Math.round(remise * total * 100) / 100);

    total_ht = total * (1 - remise);
    $('#totaux > table > tbody > tr').find('td > #total_ht').text(Math.round(total_ht * 100) / 100);
    $("#total_ht_auto").val(Math.round(total_ht * 100) / 100);

    total_tva = total_ht * $('#totaux > table > tbody > tr').find('td > input[name*="tva"]').val();
    $('#totaux > table > tbody > tr').find('td > #total_tva').text(Math.round(total_tva * 100) / 100);

    total_ttc = total_ht + total_tva;
    $('#totaux > table > tbody > tr').find('td > #total_ttc').text(Math.round(total_ttc * 100) / 100);

    pourc_acompte = $('#totaux > table > tbody > tr').find('td > input[name*="pourc_acompte"]').val();
    net_a_payer = total_ttc * pourc_acompte;
    $('#totaux > table > tbody > tr').find('td > #net_a_payer').text(Math.round(net_a_payer * 100) / 100);

    acompte_verse = $('#totaux > table > tbody > tr').find('td > input[name*="acompte_verse"]').val();
    montant_reste = total_ttc - acompte_verse;
    $('#totaux > table > tbody > tr').find('td > #montant_reste').text(Math.round(montant_reste * 100) / 100);
}