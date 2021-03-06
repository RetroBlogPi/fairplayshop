$(document).ready(function(){
	$('select#id_country').change(function(){
		updateState();
	});
	$('select#inv_id_country').change(function(){
		updateInvoiceState();
	});
	updateState();
	updateInvoiceState();
});

function updateState()
{
	$('select#id_state option:not(:first-child)').remove();
		var states = countries[$('select#id_country').val()];
		if( typeof(states) != 'undefined' )
		{
			for (indexState in states)
			{
				//ie bug fix
				if (indexState != 'indexOf')
					$('select#id_state').append('<option value="'+indexState+'"'+ (idSelectedCountry == indexState ? ' selected="selected' : '') + '">'+states[indexState]+'</option>');
			}
			$('p.id_state:hidden').slideDown('slow');
		}
		else
			$('p.id_state').slideUp('fast');
}

function updateInvoiceState()
{
	$('select#inv_id_state option:not(:first-child)').remove();
		var states = countries[$('select#inv_id_country').val()];
		if( typeof(states) != 'undefined' )
		{
			for (indexState in states)
			{
				//ie bug fix
				if (indexState != 'indexOf')
					$('select#inv_id_state').append('<option value="'+indexState+'"'+ (idInvoiceSelectedCountry == indexState ? ' selected="selected' : '') + '">'+states[indexState]+'</option>');
			}
			$('p.inv_id_state:hidden').slideDown('slow');
		}
		else
			$('p.inv_id_state').slideUp('fast');
}
