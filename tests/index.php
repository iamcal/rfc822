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
	width:80px;
	margin:0 3px 0 3px;
	padding:3px;
	text-align: center;
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
	include('runner.php');

#echo "<pre>";
#echo HtmlSpecialChars(print_r($tests, 1));
#echo HtmlSpecialChars(print_r($totals, 1));
#echo "</pre>";
?>

<p>
	This test suite comes from <a href="http://www.dominicsayers.com/isemail/">Dominic Sayers</a> and is a mix of RFC examples and examples from other validators.
</p>
<p>
	The test suite is currently allowing addresses that are unroutable on the public Internet, such as <code>first.last@example.123</code>.
	This behavior is disabled by default, but can be switched on; the tests are run in both modes for comparison.
</p>

<div class="isemail isemail_tooltip">
	<p class="isemail_address"><br />&nbsp;</p>

	<p class="isemail_result isemail_header">Expected</p>
	<p class="isemail_result isemail_header">Strict Mode</p>
	<p class="isemail_result isemail_header">Public Mode</p>
</div>

<div class="isemail isemail_tooltip">
	<p class="isemail_address">Percent correct</p>
	<p class="isemail_result isemail_expected">-</p>
	<p class="isemail_result isemail_expected"><?=floor(100 * $totals['strict'] / $totals['all'])?>%</p>
	<p class="isemail_result isemail_expected"><?=floor(100 * $totals['public'] / $totals['all'])?>%</p>
</div>

<div class="isemail isemail_tooltip">
	<p class="isemail_address">Number correct</p>
	<p class="isemail_result isemail_expected">-</p>
	<p class="isemail_result isemail_expected"><?=$totals['strict']?> / <?=$totals['all']?></p>
	<p class="isemail_result isemail_expected"><?=$totals['public']?> / <?=$totals['all']?></p>
</div>


<? foreach ($tests as $test){ ?>

<div class="isemail isemail_tooltip">
	<span>
		Test # <?=$test['id']?><br />
		<strong><?=show_escapes(HtmlSpecialChars($test['address']))?></strong><br />
		Expected result: <?=is_valid($test['expected'])?><br />
<? if ($test['comment']){ ?>
		Comment: <?=HtmlSpecialChars($test['comment'])?><br />
<? } ?>
		Source: <?=HtmlSpecialChars($test['source'])?>
	</span>
	<p class="isemail_address"><nobr><a href="<?=HtmlSpecialChars($test['sourcelink'])?>" target="_blank"><?=show_escapes(HtmlSpecialChars($test['address']))?></a></nobr></p>
	<p class="isemail_result isemail_expected"><?=is_valid($test['expected'])?></p>

	<p class="isemail_result isemail_<?=$test['result_strict']==$test['expected']?'':'un'?>expected"><?=is_valid($test['result_strict'])?></p>
	<p class="isemail_result isemail_<?=$test['result_public']==$test['expected']?'':'un'?>expected"><?=is_valid($test['result_public'])?></p>
</div>

<? } ?>

<p style="clear: both; padding-bottom: 50px;">&nbsp;</p>

</body>
</html>
