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

define('AJAX_SCRIPT', 1);

require('../../../config.php');

require_login();

$blockid = required_param('blockid', PARAM_TEXT);
$key = required_param('wikikey', PARAM_TEXT);

$wikikeys = $_SESSION['wikipediakeys'][$blockid];

$url = $wikikeys[$key];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Moodle WikiChecker');
curl_setopt($ch, CURLOPT_POSTFIELDS, '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml charset=UTF-8"));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

// check for proxy
if (!empty($CFG->proxyhost) and !is_proxybypass($uri)) {
    // SOCKS supported in PHP5 only
    if (!empty($CFG->proxytype) and ($CFG->proxytype == 'SOCKS5')) {
        if (defined('CURLPROXY_SOCKS5')) {
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        } else {
            curl_close($ch);
            print_error( 'socksnotsupported','mnet' );
        }
    }

    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, false);

    if (empty($CFG->proxyport)) {
        curl_setopt($ch, CURLOPT_PROXY, $CFG->proxyhost);
    } else {
        curl_setopt($ch, CURLOPT_PROXY, $CFG->proxyhost.':'.$CFG->proxyport);
    }

    if (!empty($CFG->proxyuser) and !empty($CFG->proxypassword)) {
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $CFG->proxyuser.':'.$CFG->proxypassword);
        if (defined('CURLOPT_PROXYAUTH')) {
            // any proxy authentication if PHP 5.1
            curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC | CURLAUTH_NTLM);
        }
    }
}

$res = curl_exec($ch);

// check for curl errors
$curlerrno = curl_errno($ch);
if ($curlerrno != 0) {
    $errormess = "Request for $url failed with curl error $curlerrno";
}

// check HTTP error code
$info =  curl_getinfo($ch);
if (!empty($info['http_code']) and ($info['http_code'] != 200)) {
    $errormess = "Request for $uri failed with HTTP code ".$info['http_code'];
}

curl_close($ch);

if (!empty($res)) {
    if (preg_match('/Disambig.svg/', $res)) {
        echo json_encode(array($key, 'AMBIG', get_string('ambigstatus', 'filter_wikipediacalls')));
    }
    if (preg_match('/noarticletext|page-Special_Badtitle/', $res)){
        echo json_encode(array($key, 'NOOK', get_string('nookstatus', 'filter_wikipediacalls')));
    }
    echo json_encode(array($key, 'OK', get_string('okstatus', 'filter_wikipediacalls')));
} else {
    echo json_encode(array($key, 'ERROR', $errormess));
}
