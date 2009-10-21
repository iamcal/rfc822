<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html><head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<title>Cal Henderson - RFC-compliant email address validation</title>

<style type="text/css">

body {
	font-family:Calibri,Tahoma,Verdana,Arial,Helvetica,sans serif;
	color: #264E62;
}

p,h1,h2,h3,h4,h5,h6 {
	font-family:inherit;
	color:inherit;
	padding-left:7px;
	padding-right:7px;
	margin-top:0px;
	margin-left:8px;
	margin-right:8px;
}

a:link {
	text-decoration: none;
	color: #266139;
}
a:visited {
	text-decoration: none;
	color: #34854E;
}
a:hover {
	text-decoration: underline;
	color: #7EA58A;
}
a:active {
	text-decoration: none;
	color: #264E62;
}
a img {
	border-style: none;
	vertical-align:baseline;
}

h1, h2, h3, h4, h5, h6 {
	margin-bottom: 1em;
}

h1 {
	color: #346A85;
}

.isemail {
	font-size:12px;
	clear:left;
	margin:0;
	padding:0;
}

.isemail_address {
	line-height:12pt;
	float:left;
	overflow:hidden;
	width:250px;
	margin:0 0 0 8px;
	padding:3px;
}

.isemail_result {
	float:left;
	width:60px;
	margin:0 3px 0 3px;
	padding:3px;
}

.isemail_expected			{background-color:white;}
.isemail_unexpected			{background-color:#FFCCCC;}
.isemail_header				{background-color:#CCCCCC;margin-bottom:3px;}

.isemail_tooltip			{position:relative;}
.isemail_tooltip:hover span	{display: block;}
.isemail_tooltip span {
	display: none;
	position: absolute;
	top: 20px;
	left: 10px;
	padding: 5px;
	z-index: 100;
	background-color: #323232;
	color: #fff;
	width:auto;
	-moz-border-radius: 5px; /* this works only in camino/firefox */
	-webkit-border-radius: 5px; /* this is just for Safari */
}

</style>

</head>

<body>

<h1>RFC-compliant email address validation</h1>

<?
	include('rfc822.php');
	include('rfc2822.php');
	include('rfc3696.php');


	#
	# read the tests (PHP 4 compatible!)
	#

	$test = array();
	$tests = array();
	$last_key = '?';
	
	$parser = xml_parser_create();

	function start($parser, $element_name, $element_attrs){
		$GLOBALS[last_key] = StrToLower($element_name);
	}

	function stop($parser, $element_name){
		$GLOBALS[last_key] = '?';
		if ($element_name == 'TEST'){
			$GLOBALS[tests][$GLOBALS[test][id]] = $GLOBALS[test];
			$GLOBALS[test] = array();
		}
	}

	function char($parser, $chr){
		$chr = str_replace('\\0', "\0", $chr);
		if ($GLOBALS[last_key] == 'tests') return;
		if ($GLOBALS[last_key] == 'test') return;
		if ($GLOBALS[last_key] == '?') return;
		$GLOBALS[test][$GLOBALS[last_key]] .= $chr;
	}

	xml_set_element_handler($parser, "start", "stop");
	xml_set_character_data_handler($parser, "char");
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

	$fp = fopen("tests.xml", "r");

	while ($data = fread($fp, 4096)){
		xml_parse($parser, $data, feof($fp)) or die (sprintf("XML Error: %s at line %d", xml_error_string(xml_get_error_code($parser)), xml_get_current_line_number($parser)));
	}
	xml_parser_free($parser);

#echo "<pre>";
#var_export($tests[124]);
#echo "</pre>";
#exit;


	#
	# run the tests
	#

	$totals = array();

	foreach ($tests as $k => $v){

		$tests[$k][expected] = ($v[valid] == 'true') ? 1 : 0;
		$tests[$k][result_822]  = is_rfc822_valid_email_address( $v[address]) ? 1 : 0;
		$tests[$k][result_2822] = is_rfc2822_valid_email_address($v[address]) ? 1 : 0;
		$tests[$k][result_3696] = is_rfc3696_valid_email_address($v[address]) ? 1 : 0;

		$totals[all]++;
		$totals[822]  += ($tests[$k][result_822]  == $tests[$k][expected]) ? 1 : 0;
		$totals[2822] += ($tests[$k][result_2822] == $tests[$k][expected]) ? 1 : 0;
		$totals[3696] += ($tests[$k][result_3696] == $tests[$k][expected]) ? 1 : 0;

		unset($tests[$k][valid]);
	}

	function is_valid($x){
		return $x ? 'Valid' : 'Invalid';
	}

	function show_escapes($s){
		return str_replace(array("\r","\n"," ","\0"), array("&amp;#13;","&amp;#10;","&nbsp;","&amp;#0;"), $s);
	}

#echo "<pre>";
#echo HtmlSpecialChars(print_r($tests, 1));
#echo HtmlSpecialChars(print_r($totals, 1));
#echo "</pre>";
?>

<p>
	This test suite comes from <a href="http://www.dominicsayers.com/isemail/">Dominic Sayers</a> and is a mix of RFC examples and examples from other validators.
	Some of these addresses are <i>not</i> RFC822 or RFC2822 compliant, so failures are expected.
	The RFC3696 validator attempts to validate email addresses on the public internet, so is probably what you care about.
</p>

<div class="isemail isemail_tooltip">
	<p class="isemail_address"><br />&nbsp;</p>

	<p class="isemail_result isemail_header">Expected</p>
	<p class="isemail_result isemail_header">RFC 822</p>
	<p class="isemail_result isemail_header">RFC 2822</p>
	<p class="isemail_result isemail_header">RFC 3696</p>
</div>

<div class="isemail isemail_tooltip">
	<p class="isemail_address">Percent correct</p>
	<p class="isemail_result isemail_expected">-</p>
	<p class="isemail_result isemail_expected"><?=floor(100 * $totals[822] / $totals[all])?>%</p>
	<p class="isemail_result isemail_expected"><?=floor(100 * $totals[2822] / $totals[all])?>%</p>
	<p class="isemail_result isemail_expected"><?=floor(100 * $totals[3696] / $totals[all])?>%</p>

</div>


<? foreach ($tests as $test){ ?>

<div class="isemail isemail_tooltip">
	<span>
		Test # <?=$test[id]?><br />
		<strong><?=show_escapes(HtmlSpecialChars($test[address]))?></strong><br />
		Expected result: <?=is_valid($test[expected])?><br />
<? if ($test[comment]){ ?>
		Comment: <?=HtmlSpecialChars($test[comment])?><br />
<? } ?>
		Source: <?=HtmlSpecialChars($test[source])?>
	</span>
	<p class="isemail_address"><nobr><a href="<?=HtmlSpecialChars($test[sourcelink])?>" target="_blank"><?=show_escapes(HtmlSpecialChars($test[address]))?></a></nobr></p>
	<p class="isemail_result isemail_expected"><?=is_valid($test[expected])?></p>
	<p class="isemail_result isemail_<?=$test[result_822] ==$test[expected]?'':'un'?>expected"><?=is_valid($test[result_822] )?></p>
	<p class="isemail_result isemail_<?=$test[result_2822]==$test[expected]?'':'un'?>expected"><?=is_valid($test[result_2822])?></p>
	<p class="isemail_result isemail_<?=$test[result_3696]==$test[expected]?'':'un'?>expected"><?=is_valid($test[result_3696])?></p>
</div>

<? } ?>

<p style="clear: both">&nbsp;</p>

</body>
</html>