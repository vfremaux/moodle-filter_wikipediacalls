

function launch_checks(wwwroot, blockid, blockkeys){

    url = wwwroot+'/filter/wikipediacalls/ajax/keychecks.php?';

    spanid = new Array();

    for (k in blockkeys) {
        params = 'blockid='+blockid+'&wikikey='+blockkeys[k];
        spanid[blockkeys[k]] = "#span_"+blockkeys[k];

        $(spanid[blockkeys[k]]).html('<img src="'+wwwroot+'/filter/wikipediacalls/pix/ajaxbar.gif" />');
        $(spanid[blockkeys[k]]).attr('class', '');

        $.get(url + params, function(data, textstatus) {
            $(spanid[data[0]]).html(data[2]);
            $(spanid[data[0]]).attr('class', 'wikipediacalls-'+data[1]);
        });
    }
}
