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

defined('MOODLE_INTERNAL') || die();

//////////////////////////////////////////////////////////////
// 
//  This filter allows making calls to wikipedia.
//
//////////////////////////////////////////////////////////////

/// This is the filtering function itself.  It accepts the 
/// courseid and the text to be filtered (in HTML form).

class filter_wikipediacalls extends moodle_text_filter {

    function filter($text, array $options = array()) {
        global $USER, $CFG, $PAGE, $COURSE;
        global $wikiBlockCount;
        static $needcss = true;

        $PAGE->requires->js('/filter/wikipediacalls/js/module.js');

        $config = get_config('filter_wikipediacalls');

        // setting hardware default if never set before
        if (!isset($config->baseurl)) set_config('baseurl', "http://<%%LANG%%>.wikipedia.org/wiki/", 'filter_wikipediacalls');

        // Filters special tags inserts
            // get word before [WP] marker
           // replace word and marker by wikipedia link (language aware) 
        $lang = substr(@$USER->lang, 0, 2);
        if ($lang == '') $lang = "fr";

        $defaultwikibase = str_replace('<%%LANG%%>', $lang, $config->baseurl);

        // this collects all wikipedia keys for reporting
        if ($PAGE->user_is_editing() && $config->showkeys) {

             $WPKeys = array();
              preg_match_all("/(\\w+)\\[WP\\]/", $text, $matches, PREG_PATTERN_ORDER);

              foreach ($matches[1] as $aMatch) {
                    $WPKeys[urlencode($aMatch)] = $defaultwikibase . urlencode($aMatch);
              }

             preg_match_all("/(\\w+)\\[WP\\|([^\\|]*)\\|([^\\]]+)\\]/", $text, $matches, PREG_PATTERN_ORDER);
             for ($i = 0 ; $i < count($matches[1]) ; $i++) {
                 $wikibase = str_replace("<%%LANG%%>", $matches[3][$i], $config->baseurl);
                 if (!empty($matches[2][$i])) {
                       $WPKeys[urlencode($matches[2][$i])] = $wikibase . str_replace('+', '_', urlencode($matches[2][$i]));
                 } else {
                      $WPKeys[urlencode($matches[1][$i])] = $wikibase . str_replace('+', '_', urlencode($matches[1][$i]));
                 }
             }

              preg_match_all("/(\\w+)\\[WP\\|([^|\\]]+)\\]/", $text, $matches, PREG_PATTERN_ORDER);

              foreach($matches[2] as $aMatch) {
                    $WPKeys[urlencode($aMatch)] = $defaultwikibase . str_replace('+', '_', urlencode($aMatch));
              }
         }

        // this inserts any wikipedia calls
        $text = preg_replace("/(\\w+)\\[WP\\]/", "<a href=\"{$defaultwikibase}\\1\" target=\"_blank\">\\1</a>", $text); 
        $text = preg_replace("/(\\w+)\\[WP\\|([^\\|\\]]+)\\|([^|\\]]+)\\]/", "<a href=\"".str_replace("<%%LANG%%>", "\\3", $config->baseurl)."\\2\" target=\"_blank\">\\1</a>", $text); 
        $text = preg_replace("/(\\w+)\\[WP\\|\\|([^|\\]]+)\\]/", "<a href=\"".str_replace("<%%LANG%%>", "\\2", $config->baseurl)."\\1\" target=\"_blank\">\\1</a>", $text); 
        $text = preg_replace("/(\\w+)\\[WP\\|([^\\]]+)\\]/", "<a href=\"{$defaultwikibase}\\2\" target=\"_blank\">\\1</a>", $text); 
        $text = preg_replace("/\\[WP\\]/", '', $text);
    
        // this prepare wikipedia reports and testing invocator
        if ($PAGE->user_is_editing() && $config->showkeys) {
            if (count($WPKeys)) {
                if ($needcss) {
                    $text = "<link rel=\"stylesheet\" type=\"text/css\" src=\"{$CFG->wwwroot}/filter/wikipedialinks/styles.css\" />\n".$text;
                }

                $text = $text . '<div class="wikipediacalls-report"><br/>' . get_string('wikipediakeys', 'filter_wikipediacalls') . ' : <br>' . implode('<br>', $WPKeys);

                // pass all keys and call data to session for checking
                if (!isset($wikiBlockCount)) {
                    $wikiBlockCount = 0;
                }
                $_SESSION['wikipediakeys'][$wikiBlockCount] = $WPKeys;
    
                // if link code is not loaded, load link code
                $wikipediacallslinklabelstr = get_string('wikipediacallslinklabel', 'filter_wikipediacalls');
                // REM : no include can be used here as effective code production is delayed
                $testCallsLink = "<p><a href=\"Javascript:wf_openCallCheckWindow('{$wikiBlockCount}', '{$COURSE->id}', '{$CFG->wwwroot}')\">$wikipediacallslinklabelstr</a></p>";
                $text = $text . $testCallsLink.'</div>';
                $wikiBlockCount++;
            }
        }

        // special views for developper
        /*
        if (has_capability('moodle/site:config', context_system::instance())) {
            if (preg_match("/\[MOODLE_CFG\]/", $text)) {
                 print_r($CFG);
            }
            if (preg_match("/\[MOODLE_COURSE\]/", $text)) {
                 print_r($COURSE);
            }
            if (preg_match("/\[MOODLE_SESSION\]/", $text)) {
                 print_r($_SESSION);
            }
        }
        */

        return $text;  // Look for all these words in the text
    }
}
