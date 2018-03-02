<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    filter
 * @subpackage wikipediacalls
 * @copyright  2006 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../config.php');

$wikiblockid = required_param('wikiblockid', PARAM_INT);
$id = required_param('id', PARAM_INT);
$url = new moodle_url('/filter/wikipediacalls/testWikipediaCalls.php', array('id' => $id, 'wikiblockid' => $wikiblockid));
$PAGE->set_url($url);

// Security.
require_login();

// Script parameters
$wikikeys = $_SESSION['wikipediakeys'][$wikiblockid];

$PAGE->set_pagelayout('popup');
$PAGE->set_context(context_course::instance($id));
$PAGE->requires->jquery();
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
        $link = $wikikeys[$k].' - <a href="'.$wikikeys[$k].'" target="_blank" title="'.get_string('seewppage', 'filter_wikipediacalls').'">'.$OUTPUT->pix_icon('t/hide').'</a>';
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