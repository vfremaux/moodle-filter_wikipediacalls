<?php  //$Id: filtersettings.php,v 1.1.2.2 2007/12/19 17:38:45 vf Exp $

// this will give same access to original multilang setting as we share it
$settings->add(new admin_setting_configcheckbox('filter_wikipediacalls_showkeys', get_string('showkeys', 'filter_wikipediacalls'), get_string( 'configshowkeys','filter_wikipediacalls' ), 0));
$settings->add(new admin_setting_configtext('filter_wikipediacalls_baseurl', get_string('baseurl','filter_wikipediacalls' ), get_string('configbaseurl','filter_wikipediacalls'), "http://<%%LANG%%>.wikipedia.org/wiki/"));

?>
