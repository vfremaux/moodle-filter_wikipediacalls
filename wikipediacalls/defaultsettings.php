<?php
    // check/set default config settings for wikipediacalls
    
function wikipediacalls_defaultsettings( $force=false  ) {

     global $CFG;
    
     if (!isset($CFG->filter_wikipediacalls_showkeys) or $force) {
        if (isset($CFG->filter_wikipediacalls_showkeys)) {
            set_config( 'filter_wikipediacalls_showkeys', !$CFG->filter_wikipediacalls_showkeys );
            set_config( 'filter_wikipediacalls_showkeys', '' );
        }
        else {
            set_config( 'filter_wikipediacalls_showkeys', 1 );
        }
    }
     if (!isset($CFG->filter_wikibaseurl) or $force) {
        if (isset($CFG->filter_wikibaseurl)) {
            set_config( 'filter_wikibaseurl', !$CFG->filter_wikibaseurl );
            set_config( 'filter_wikibaseurl', '' );
        }
        else {
            $lang = substr($CFG->lang, 0, 2);
            set_config( 'filter_wikibaseurl', "http://<%%LANG%%>.wikipedia.org/wiki/" );
        }
    }
}
?>
