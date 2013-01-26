<?php
require('../../config.php');

require_login();

if (isguest()) {
  redirect($CFG->wwwroot);
}

// Script parameters
$wikiBlockId = required_param('wikiBlockId', PARAM_INT);
$wikiKeys = $_SESSION['wikipediaKeys'][$wikiBlockId];

// Stylesheets
$stylesheetshtml = '';
foreach ($CFG->stylesheets as $stylesheet) {
  $stylesheetshtml .= '<link rel="stylesheet" type="text/css" href="' . $stylesheet . '" />';
}

/// Select encoding
    $encoding = 'ISO-8859-1';
    if (function_exists('current_charset')){
        $encoding = current_charset();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title> </title>
<meta http-equiv="content-type" content="text/html; charset=<?= $encoding ?>" />
<?php echo $stylesheetshtml ?>
</head>
<body>
<style>
BODY { font-family : 'Arial' }
.OK { color : green ; font-weight : bold }
.AMBIG { color : purple ; font-weight : bold }
.NOOK { color : red ; font-weight : bold }
.CHECKING { color : blue ; font-weight : bold }
.UNCHECKED { color : orange ; font-weight : bold }
</style>
<h2><?php echo get_string('wikipediakeyscheck', 'filter_wikipediacalls') ?></h2>
<p><table width="90%">
<?php
if (count($wikiKeys) == 0){
?>
	<tr>
		<td>
			<?php print_string('nokeytocheck', 'filter_wikipediacalls') ?>
		</td>
   </tr>
<?php
} else {
	foreach(array_keys($wikiKeys) as $aKey){
?>
	<tr>
		<td>
			<?php echo $wikiKeys[$aKey] ?> - <a href="<?php echo $wikiKeys[$aKey] ?>" target="_blank" title="<?php print_string('seewppage', 'filter_wikipediacalls') ?>"><img src="<?php echo $CFG->wwwroot ?>/pix/t/hide.gif" border="0"></a>
		</td>
		<td>
			<span id="span_<?php echo $aKey ?>" class="UNCHECKED"><?php echo mb_convert_encoding(get_string('uncheckedstatus', 'filter_wikipediacalls'), "auto", $encoding) ?></span>
		</td>
	</tr>
<?php
	}
}
?>
	<tr>
		<td colspan="2">
			<a href="Javascript:launchTheChecks();"><?php echo get_string('launchlink', 'filter_wikipediacalls') ?></a> -
			<a href="Javascript:self.close();"><?php echo get_string('closewindow', 'filter_wikipediacalls') ?></a>		
		</td>
	</tr>
</table>

<?php
if (count($wikiKeys) > 0){
?>
<!-- Ajax checks -->
<script type="text/javascript">
// launches Http request for asynchronous handshaking
function launchHttpRequest(url, keyword){
	var markerObj = document.getElementById("span_" + keyword);
	if (!markerObj){
		 alert("span_" + keyword + " span not found\n");
		 return;
	}
	markerObj.innerHTML = "<?php echo get_string('checkingstatus', 'filter_wikipediacalls') ?>...";
	markerObj.className = "CHECKING";
   var xmlHttp = GetXmlHttpObject();
   if (xmlHttp == null) {
      alert ("Ajax Error");
      return;
   }
   xmlHttp.onreadystatechange = function(){
			if ((xmlHttp.readyState == 4) || (xmlHttp.readyState == 'complete')){
			   var response = xmlHttp.responseText;
			   // alert(response);
			   re1 = new RegExp("page-Special_Badtitle");
			   re2 = new RegExp("noarticletext");
			   re3 = new RegExp("Disambig.svg");
			   if(!response.match(re1) && !response.match(re2)){
			   	if(!response.match(re3)){
			   		markerObj.innerHTML = "<?php echo get_string('okstatus', 'filter_wikipediacalls') ?>";
			   		markerObj.className = "OK";
			   	}
			   	else{
			   		markerObj.innerHTML = "<?php echo get_string('ambigstatus', 'filter_wikipediacalls') ?>";
			   		markerObj.className = "AMBIG";
			   	}
		   	}
		   	else{
		   		markerObj.innerHTML = "<?php echo get_string('nookstatus', 'filter_wikipediacalls') ?>";
		   		markerObj.className = "NOOK";
		      }
	   		xmlHttp = null;
			}
		};
   xmlHttp.open("GET", url, true);
   xmlHttp.send(null);
}

// intanciates an XMLHttpRequest friendly with all browser types
function GetXmlHttpObject(){
   var objXmlHttp = null;
   // for Gecko browsers
   if (window.XMLHttpRequest){
      objXmlHttp = new XMLHttpRequest();
   }
   // for IE browsers
   else if (window.ActiveXObject){
      objXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
   }
   return objXmlHttp;
}

function launchTheChecks(){
	var message = '<?php echo get_string('accesssettingmessage','filter_wikipediacalls') ?>';
	re = /\<br\>/g;
	message = message.replace(re, "\n");
	if (confirm(message)){
<?php
	foreach(array_keys($wikiKeys) as $aKey){
?>	
	launchHttpRequest('<?php echo $wikiKeys[$aKey] ?>','<?php echo $aKey ?>');
<?php
	}
?>
	}
}

</script>
<!-- -->
<?php
}
?>

</body>
</html>