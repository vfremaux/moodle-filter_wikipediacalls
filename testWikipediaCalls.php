<?php

	require('../../config.php');
	
	require_login();
	
	// Script parameters
	$wikiblockid = required_param('wikiblockid', PARAM_INT);
	$id = required_param('id', PARAM_INT);
	$wikikeys = $_SESSION['wikipediakeys'][$wikiblockid];
	
	$PAGE->set_url($CFG->wwwroot.'/filter/wikipediacalls/testWikipediaCalls.php');
	$PAGE->set_pagelayout('popup');
	$PAGE->set_context(context_course::instance($id));
	$PAGE->requires->js('/filter/wikipediacalls/js/jquery-1.8.2.min.js', true);
	$PAGE->requires->js('/filter/wikipediacalls/js/keychecks.js', true);
	$PAGE->requires->css('/filter/wikipediacalls/styles.css', true);
	$strtitle = get_string('wikipediakeyscheck', 'filter_wikipediacalls');
    $PAGE->set_title($strtitle);
    $PAGE->set_heading($SITE->fullname);

	echo $OUTPUT->header();	

	echo $OUTPUT->heading(get_string('wikipediakeyscheck', 'filter_wikipediacalls'));
	
	$jsarray = array();

	if (count($wikikeys) == 0){
		$OUTPUT->notification(get_string('nokeytocheck', 'filter_wikipediacalls'));
	} else {
		$table = new html_table();
		$table->head = array('', '');
		$table->width = '90%';
		$table->size = array('40%', '60%');
		foreach(array_keys($wikikeys) as $k) {
			$link = $wikikeys[$k].' - <a href="'.$wikikeys[$k].'" target="_blank" title="'.get_string('seewppage', 'filter_wikipediacalls').'"><img src="'.$OUTPUT->pix_url('t/hide').'"></a>';
			$state = '<span id="span_'.$k.'" class="wikipediacalls-UNCHECKED">'.get_string('uncheckedstatus', 'filter_wikipediacalls').'</span>';
			$table->data[] = array($link, $state);
			$jsarray[] = " '$k'";  
		}
		echo html_writer::table($table);
	}
	
	$linklaunchstr = get_string('launchlink', 'filter_wikipediacalls');
	$messagestr = get_string('accesssettingmessage','filter_wikipediacalls');
	$linkclosestr = get_string('closewindow', 'filter_wikipediacalls');

	echo '<table width="100%"><tr><td colspan="2">';
	echo '<script type="text/javascript">';
	echo 'blockkeys = ['. implode(',', $jsarray).'];';
	echo '</script>';
	echo "<a href=\"Javascript:launch_checks('{$CFG->wwwroot}', '{$wikiblockid}', blockkeys);\">{$linklaunchstr}</a> - ";
	echo "<a href=\"Javascript:self.close();\">{$linkclosestr}</a>";		
	echo '</td></tr></table>';

	echo $OUTPUT->footer();