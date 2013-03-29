

function launch_checks(wwwroot, blockid, blockkeys){

	url = wwwroot+'/filter/wikipediacalls/ajax/keychecks.php?';
	
	spanid = new Array();

	for (k in blockkeys){
		params = 'blockid='+blockid+'&wikikey='+blockkeys[k];
		spanid[blockkeys[k]] = "#span_"+blockkeys[k];

		$(spanid[blockkeys[k]]).html('<img src="'+wwwroot+'/filter/wikipediacalls/pix/ajaxbar.gif" />');
		$(spanid[blockkeys[k]]).attr('class', '');

		$.get(url + params, function(data, textstatus){
			status = eval(data);
			$(spanid[status[0]]).html(status[2]);
			$(spanid[status[0]]).attr('class', 'wikipediacalls-'+status[1]);
		});
	}
}
