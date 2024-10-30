if(Autocompleter.framework == 'scriptaculous'){
	document.observe("dom:loaded", function() {
		var newDiv = new Element('div', {
			'id': 'search_results',
			'style' : 'margin-top: 28px;'
		});
		$$('body').first().insert(newDiv);
		$('search_results').addClassName('ac_results');
		if(Autocompleter.selectortype == 'id'){
			setAutoCompleter($(Autocompleter.searchfield_id));
		} else {
			$$('input[name=' + Autocompleter.searchfield_id + ']').each(function(elm){
				setAutoCompleter(elm);
			});
		}
		
		function setAutoCompleter(elm){
			new Ajax.Autocompleter(elm, 'search_results', Autocompleter.ajaxurl, {
				paramName: 'q',
				parameters: 'action=autocompleter_results&lang=' + Autocompleter.lang,
				tokens: ','
			});
		}
	});
} else {
	var selector =	(Autocompleter.selectortype == 'name') ? 'input[name=' + Autocompleter.searchfield_id + ']' : '#' + Autocompleter.searchfield_id;
	jQuery(selector).each(function(i){
		jQuery(this).autocomplete(Autocompleter.ajaxurl + '?action=autocompleter_results&lang=' + Autocompleter.lang, {
			formatItem: function(value){
				return value[0];
			},
			formatResult: function(value) {
				return value[0].replace(/<\/?[^>]+>/gi, '');
			}
		})
		.result(function(event, value){
			location.href = value[1];
		});
	});
}