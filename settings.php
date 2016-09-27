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

$lang = substr($CFG->lang, 0, 2);

// this will give same access to original multilang setting as we share it
$settings->add(new admin_setting_configcheckbox('filter_wikipediacalls/showkeys', get_string('showkeys', 'filter_wikipediacalls'), get_string( 'configshowkeys','filter_wikipediacalls' ), 0));
$settings->add(new admin_setting_configtext('filter_wikipediacalls/baseurl', get_string('baseurl','filter_wikipediacalls' ), get_string('configbaseurl','filter_wikipediacalls'), "http://<%%LANG%%>.wikipedia.org/wiki/"));
