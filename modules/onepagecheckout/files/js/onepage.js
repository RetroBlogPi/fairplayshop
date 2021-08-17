function invoice_address() {
	var f = document.getElementById("invoice_address_form");
	if (document.getElementById("addressesAreEquals").checked) { f.style.display='none';
	}
	else {
		f.style.display='block';
	} 

} 


var countries_zones = new Array();
var recentZone = 0;

 function carriers_display(id_country) {
  if (id_country > 0)
  {
   if (recentZone > 0) {
     document.getElementById('zone_'+recentZone).style.display = 'none';
   }
   recentZone = countries_zones[id_country];
   document.getElementById('zone_'+recentZone).style.display = 'block';
  }
 }
 function add_country_zone(id_country, id_zone) {
   countries_zones[id_country] = id_zone;
 }
 function getRadioNum(radioName) {
   var rads = document.getElementsByName(radioName);

   return rads.length;
 }


//fix for IE's getElementsByName
if(typeof(window.external) != 'undefined'){
document.getElementsByName = function(name){
    var elems = document.getElementsByTagName('input');
    var res = []
    for(var i=0;i<elems.length;i++){
        att = elems[i].getAttribute('name');
        if(att == name) {
            res.push(elems[i]);
        }
    }
    return res;
}
} 

 function getRadioVal(radioName) {
   var rads = document.getElementsByName(radioName);

   //fix for IE's 2 radio buttons selected
   var selected_count = 0;
   var selected_radio_value = null;

   for(rad=0;rad<rads.length;rad++) {
     if(rads[rad].checked)
       //return rads[rad].value;
     {
	if (selected_count > 0) {
	  //uncheck already selected value
	  rads[rad].checked = false;
	} else {
	  selected_radio_value = rads[rad].value;
          selected_count++;
	}
     }
   }
   return selected_radio_value;
 }
 
 function updateTotals(total_delivery_id, total_price_id, carrier_price_id) {
	delivery_price = $(total_delivery_id).text();
	new_carrier_price = $(carrier_price_id).val();
	total_price = $(total_price_id).text();
	if (delivery_price == '' || total_price == '') { return; }

	has_comma = (total_price.indexOf(',') > 0);
	
	delivery_price_uniform = delivery_price.replace(',','.');
	new_carrier_price_uniform = new_carrier_price.replace(',','.');
	total_price_uniform = total_price.replace(',','.');

	actualTotalShipping = parseFloat(delivery_price_uniform.replace(/[^\d,.]/g,''));
	newTotalShipping = parseFloat(new_carrier_price_uniform.replace(/[^\d,.]/g,''));
	actualTotalPrice =  parseFloat(total_price_uniform.replace(/[^\d,.]/g,''));

	newTotalShippingStr = newTotalShipping.toFixed(2) + '';
	newTotalPrice = (actualTotalPrice - actualTotalShipping + newTotalShipping);
	newTotalPriceStr = newTotalPrice.toFixed(2) + '';

//				alert('[deliver_price_unifomr]actual/newTotalShippingStr = '+delivery_price_uniform+']' +actualTotalShipping +'/'+ newTotalShippingStr);
	newTotalShippingStr = delivery_price_uniform.replace(''+actualTotalShipping.toFixed(2), newTotalShippingStr);
	newTotalPriceStr = total_price_uniform.replace(''+actualTotalPrice.toFixed(2), newTotalPriceStr);

	if (has_comma) {
	  newTotalShippingStr = newTotalShippingStr.replace('.', ',');
	  newTotalPriceStr = newTotalPriceStr.replace('.',',');
	}

	$(total_delivery_id).html(newTotalShippingStr);// + ' &euro;');
	if (!isNaN(actualTotalShipping)) 
	$(total_price_id).html(newTotalPriceStr);// + ' &euro;');

}


 function set_carrier(carrier_price, manual) {


   if (carrier_price != null && carrier_price != "")
     carrier_price = parseFloat(carrier_price.replace(/[^\d,.]/g,'').replace(',','.'));

   //document.getElementById('id_carrier_hidden').value = getRadioVal('id_carrier_zone_'+recentZone);
   if (document.getElementById('id_carrier_hidden') != null) {
    document.getElementById('id_carrier_hidden').value = getRadioVal('id_carrier');
    
    var id_state = $('#id_state').val();
    if (id_state == "") {
      document.getElementById('id_zone_hidden').value = countries_zones[$('#id_country').val()];
    } else {
      if (csz.length > 0)
        document.getElementById('id_zone_hidden').value = csz[$('#id_country').val()][id_state];
    }
    document.getElementById('price_carrier_hidden').value = carrier_price;
  
    //if manually clicked, set carrier as "user choice"
    if (manual == 1) { ajaxShipp.updateShipping($('#id_carrier_hidden').val(), 1); }
    else             { ajaxShipp.updateShipping($('#id_carrier_hidden').val()); }

   
    updatePaymentIfShip2payActive();
   }

   return true;
 }


function onepage_cartupdate(doc, form, link)
{
  //set_carrier();
  doc.validate_conditions = 'no';
  form.step.value = '2';
  form.cartupdateflag.value = '1';
  form.link.value = link;
  form.submit();
}

function isShip2payActive() {
  return ($('#ship2pay_active').val() == 1);
}

function updatePaymentIfShip2payInactive() {
  if (!isShip2payActive()) {
    ajaxShipp.updatePaymentMethods();
  }
}

function updatePaymentIfShip2payActive() {
  if (isShip2payActive()) {
    ajaxShipp.updatePaymentMethods();
  }
}



var ajaxShipp = {
	
	//override every button in the page in relation to the cart
        overrideButtonsInThePage : function(){

                $('#id_country').unbind('change').change(function(){
                        ajaxShipp.country_change_handler(false);
                        updateState(); // USA states
                }),

                $('#id_state').unbind('change').change(function(){
                        ajaxShipp.state_change_handler(false);
                       // updateState(); // USA states
                }),

                $('#quick_login').unbind('click').click(function(){
                        ajaxShipp.quick_login_click_handler()
                }),

                $('#alr_click_here').unbind('click').click(function(){
                        return ajaxShipp.alr_here_click_handler()
                }),

                $('#dlv_addresses').unbind('change').change(function(){
                        ajaxShipp.dlv_addresses_handler();
                       // updateState(); // USA states
                }),

                $('#inv_addresses').unbind('change').change(function(){
                        ajaxShipp.inv_addresses_handler();
                       // updateState(); // USA states
                });

        },

        country_change_handler : function(force_display) {
                id_country_x = $('#id_country').val();
                recentZone_x = countries_zones[id_country_x];
		if ($('#input_virtual_carrier').length !=0) // virtual cart
		{
		  if (recentZone_x !== undefined && (recentZone_x != $('#input_virtual_carrier').val() || force_display))
		  {
		    ajaxShipp.updatePaymentMethods();
		    $('#input_virtual_carrier').val(recentZone_x);
		  }
		}else
                if (recentZone_x !== undefined && (recentZone_x != $('#id_zone_hidden').val() || force_display)) {
                  ajaxShipp.updateZonesCarriers(recentZone_x);
                } else {
                  ajaxShipp.updatePaymentMethods();
		}
                return false;
        },

	state_change_handler : function(force_display) {
		id_country_x = $('#id_country').val();
		id_state_x = $('#id_state').val();
		if (id_state_x !== undefined && id_state_x != "") {
                  recentZone_x = csz[id_country_x][id_state_x];
                  if (recentZone_x != $('#id_zone_hidden').val() || force_display) {
                    ajaxShipp.updateZonesCarriers(recentZone_x);
                  }
		}
                return false;

	},

        quick_login_click_handler : function() {
		email = $('#alr_email').val();
		passwd = $('#alr_passwd').val();
		
		ajaxShipp.quickLogin();
        },

        alr_here_click_handler : function() {
		
		$('#alr_body').toggle(300);
		return false;
        },

	dlv_addresses_handler : function() {
		
		var id_address =  $('#dlv_addresses').val();
		var address = dlv_addresses[id_address];
		// '{$address.id_country}','{$address.id_state}','{$address.company}','{$address.lastname}','{$address.firstname}','{$address.address1}','{$address.address2}','{$address.postcode}','{$address.city}','{$address.other}','{$address.phone}','{$address.phone_mobile}
		var start = 1000;
		var inc = 70;
		var i=0;
		var opacity = 0.2;
		$('#company').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#company', address[2]) });
		$('#firstname').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#firstname', address[4]) });
		$('#lastname').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#lastname', address[3]) });
		$('#address1').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#address1', address[5]) });
		$('#address2').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#address2', address[6]) });
		$('#postcode').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#postcode', address[7]) });
		$('#city,').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#city,', address[8]) });
		$('#id_country').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#id_country', address[0]); $('#id_country').trigger('change'); });
		$('#id_state').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#id_state', address[1]) });
		$('#phone_mobile').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#phone_mobile', address[11]) });
		$('#other').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#other', address[9]) });
		$('#company,#firstname,#lastname,#address1,#address2,#postcode,#city,#id_country,#id_state,#phone_mobile,#other').fadeTo(300, 1);

		if (address[6] != '') { $('#p_address2').show(); }
                return false;
        },

	inv_addresses_handler : function() {
		
		var id_address =  $('#inv_addresses').val();
		var address = inv_addresses[id_address];
		// '{$address.id_country}','{$address.id_state}','{$address.company}','{$address.lastname}','{$address.firstname}','{$address.address1}','{$address.address2}','{$address.postcode}','{$address.city}','{$address.other}','{$address.phone}','{$address.phone_mobile}
		var start = 1000;
		var inc = 70;
		var i=0;
		var opacity = 0.2;
		$('#inv_company').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_company', address[2]) });
		$('#inv_firstname').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_firstname', address[4]) });
		$('#inv_lastname').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_lastname', address[3]) });
		$('#inv_address1').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_address1', address[5]) });
		$('#inv_address2').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_address2', address[6]) });
		$('#inv_postcode').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_postcode', address[7]) });
		$('#inv_city,').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_city,', address[8]) });
		$('#inv_id_country').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_id_country', address[0]); ajaxShipp.country_change_handler(true); updateState();  $('#inv_id_country').trigger('change'); });
		$('#inv_id_state').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_id_state', address[1]) });
		$('#inv_phone_mobile').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_phone_mobile', address[11]) });
		$('#inv_other').fadeTo(start+inc*i++, opacity, function () { ajaxShipp.setField('#inv_other', address[9]) });
		$('#inv_company,#inv_firstname,#inv_lastname,#inv_address1,#inv_address2,#inv_postcode,#inv_city,#inv_id_country,#inv_id_state,#inv_phone_mobile,#inv_other').fadeTo(300, 1);

		if (address[6] != '') { $('#p_inv_address2').show(); }
                return false;
        },



	setField : function(fieldname, value) {
	  $(fieldname).val(value);
	},

	updateShipping : function(shippingId, userChoice){

		var userChoiceStr='';
		if (userChoice == 1) { userChoiceStr = '&userChoice=1'; }
		//send the ajax request to the server
		$.ajax({
			type: 'GET',
			url: 'order.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'ajax_carrier=' + shippingId + '&ajax_zone_id='+$('#id_zone_hidden').val()+userChoiceStr,
			success: function(jsonData)
			{
				ajaxCart.refresh();
			//	$('#order-detail-content td.price').val();

				updateTotals('#order-detail-content tr.cart_total_delivery td.price', '#order-detail-content tr.cart_total_price td.price', '#price_carrier_hidden');			
				updateTotals('#order-detail-content tr.cart_total_deliveryEx td.price', '#order-detail-content tr.cart_total_priceEx td.price', '#price_carrier_hidden');			
//				alert('success');
								//reactive the button when adding has finished
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//alert("TECHNICAL ERROR: unable to updateShipping.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
				//alert('ajax_carrier=' + shippingId + '&ajax_zone_id='+countries_zones[$('#id_country').val()]);
			}
		});
	},

	updatePaymentMethods : function(){

		// check if payment on same page is turned on
		if ($('#payment_content').length == 0)
		  return;
		
		var selectedCountry = $('#payment_country').val();
		var selectedCarrier = $('#payment_carrier').val();

		var countryId = $('#id_country').val();
		var carrierId = $('#id_carrier_hidden').val();

		if (countryId !== undefined && countryId == selectedCountry && carrierId == selectedCarrier) {	
		  return; // correct page already displayed
		}
		$('#payment_wait_img').show();
		$('#payment_content').fadeTo(600,0.2);
		//send the ajax request to the server
		$.ajax({
			type: 'GET',
			url: 'order.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'ajax_payment_country=' + countryId + '&ajax_payment_carrier=' + carrierId,
			success: function(jsonData)
			{
				if ($('#hide_payment').length) {
				  if (jsonData.num_payments == 1) { $('#payment_selection').hide(); }
				  else { $('#payment_selection').show(); }
				}
				$('#payment_content').slideUp(400, function() {$('#payment_wait_img').fadeOut(500); $('#payment_content').html(jsonData.html_data).slideDown(500).fadeTo(400, 1); });
				//$('#payment_content').html(jsonData.html_data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			}
		});
	},

	updateZonesCarriers : function(zoneId){
	
		//send the ajax request to the server
		$('#carriers_wait_img').show();
		$('#carriers2').fadeTo(600,0.2);
		$.ajax({
			type: 'GET',
			url: 'order.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'zone_carriers=' + zoneId,
			success: function(jsonData)
			{
				if ($('#hide_carrier').length) {
				  if (jsonData.num_carriers == 1) { $('#carrier_selection').hide(); }
				  else { $('#carrier_selection').show(); }
				}
				$('#carriers2').slideUp(400, function() {$('#carriers_wait_img').fadeOut(500); $('#carriers2').html(jsonData.html_data).slideDown(500).fadeTo(400, 1); set_carrier(jsonData.selected_price); updatePaymentIfShip2payInactive()});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			}
		});
	},


	quickLogin : function(){
	
		//send the ajax request to the server
		$('#alr_wait_img').show();
		$('#alr_body').fadeTo(500, 0.2);
		$.ajax({
			type: 'POST',
			url: 'authentication.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'ajax_auth=1&alr_email='+$('#alr_email').val()+'&alr_passwd='+$('#alr_passwd').val(),
			success: function(jsonData)
			{
				success_login = jsonData.success;
				//alert('success login: '+success_login);

				if (success_login == 1) {
				  $('#alr_fieldset').hide(500); 
				  window.location = 'order.php?step=1';
				} else {
				  $('#alr_body').fadeTo(1, 1);
				  $('#alr_wait_img').fadeOut(500);
				  $('#alr_error').html(jsonData.error);
				  $('#alr_error').show(400);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//alert("TECHNICAL ERROR: unable to updateZonesCarriers.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
			}
		});
	}



}//var ajaxShipp


var isFixedCart=false;
var isFixedSummary=false;
var cart_block, cart_block_anchor;
var topOffsetCart, topOffsetSummary, staticWidth;
var tfoot_static, tfoot_static_underlay, cart_summary;


function setScrollHandler() {

  var scroll_cart = $('#scroll_cart').length;
  var scroll_summary = $('#scroll_summary').length;

  if (scroll_cart || scroll_summary)
  {
	if (scroll_cart) {
  	  // cart scrolling
	  cart_block = $("#cart_block");
	  cart_block.before("<div id=\"cart_block_anchor\" style=\"display:none; height:1px;\">&nbsp;</div>");
	  cart_block_anchor = $("#cart_block_anchor");

	  topOffsetCart = cart_block.offset().top;
	}

	if (scroll_summary) {
	  // sumary scrolling
	  tfoot_static = $("#tfoot_static");
	  tfoot_static_underlay = $("#tfoot_static_underlay");
	  cart_summary = $("#cart_summary");

	  topOffsetSummary = tfoot_static.offset().top - parseFloat(tfoot_static.css('margin-top').replace(/auto/, 0));
	  staticWidth = tfoot_static.width();
	  tfoot_static_underlay.width(tfoot_static.width());
	  tfoot_static_underlay.height(tfoot_static.height());
	}


	$(window).scroll(function() {
	  var y = $(this).scrollTop();

	  if (scroll_cart) {
	    if (!isFixedCart && y >= topOffsetCart) {
	      cart_block_anchor.show();
	      cart_block.css("position", "fixed").css("top", 0);
	      cart_block_anchor.css("margin-bottom", cart_block.height()+parseFloat(cart_block.css('margin-bottom').replace(/auto/, 0)));
	      isFixedCart = true;
	    } else {
	      if (isFixedCart && y < topOffsetCart) {
	        cart_block_anchor.hide();
	        cart_block.css("position", "static");
	        cart_block_anchor.css("margin-bottom", 0);
	        isFixedCart = false;
	      }
	    }
	  }//if(scroll_cart)
	  if (scroll_summary) {
	    if (!isFixedSummary && y >= topOffsetSummary) {
	      tfoot_static_underlay.show();
	      tfoot_static.css("position", "fixed").css("top", 0).width(staticWidth);
	      cart_summary.css("margin-bottom", tfoot_static.height());
	      isFixedSummary = true;
	    } else {
	      if (isFixedSummary && y < topOffsetSummary) {
	        tfoot_static_underlay.hide();
	        tfoot_static.css("position", "static");
	        cart_summary.css("margin-bottom", 0);
	        isFixedSummary = false;
	      }
	    }
	  }//if(scroll_summary)

	});
  }//if(scroll_cart||scroll_summary)
}//setScrollHandler()


function setOrderFormGATracker() {
  var checkout_tracker = $('#checkout_tracker').length;

  if (checkout_tracker) { 
    if (typeof (pageTracker) == 'undefined') {
      alert('GA checkout form tracker is turned on, but pageTracker variable is undefined.');
    }else {
      // track all blur events on checkout form
      $('#form input,#form select,#form textarea').unbind('blur').blur(function(){
	if ($(this).val() != '')
	  pageTracker._trackPageview('/checkout-form/'+$(this).attr('name'));
	//console.info('blur: '+$(this).val()+','+$(this).attr('name'));
      });
    }
  }//if(checkout_tracker)
}//setOrderFormGATracker()

function setEmailCheck() {
  var password_shown = $('#registerme_password').length;

  if (password_shown) {
      $('#email').blur(function(){
	var email = $(this).val();
        if (email != '')
 		$.ajax({
                        type: 'GET',
                        url: 'order.php',
                        async: true,
                        cache: false,
                        dataType : "json",
                        data: 'customer_email_check=' + email,
                        success: function(jsonData)
                        {
                                  if (jsonData.result == 1) { 
				    $('#registerme_fieldset').hide();
				    $('#existing_email_msg').show();
				  }
                                  else {
				    $('#registerme_fieldset').show();
				    $('#existing_email_msg').hide();
				  }
                        }
                });
        //console.info('blur: '+$(this).val()+','+$(this).attr('name'));
      });
 
      $('#existing_email_login').click(function(){
	$('#alr_body').show(300)
	$('#alr_email').val($('#email').val());
	$('#alr_passwd').focus();
	return false;
      });
  }//if(password_shown)
}//setEmailCheck()

function setSampleHints() {
  var ex_texts = $('#ex_texts').length;

  if (ex_texts) {
      // track all blur events on checkout form
      $('#form .account_creation input').focus(function(){
	$(this).nextAll('i').removeClass('ex_blur').addClass('ex_focus');
	return false;
      });
      $('#form .account_creation input').blur(function(){
	$(this).nextAll('i').removeClass('ex_focus').addClass('ex_blur');
	return false;
      });
  }//if(ex_texts)
}//setOrderFormGATracker()


//when document is loaded...
$(document).ready(function(){
	ajaxShipp.overrideButtonsInThePage();
	ajaxShipp.country_change_handler(true);
	ajaxShipp.state_change_handler(true);

	setOrderFormGATracker();
	setEmailCheck();
	setSampleHints();
	setScrollHandler();	


//	ajaxCart.refresh();
});

