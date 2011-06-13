<?
	$title = "RFC 822 Demo";
	$no_homelink = 1;
	include('../../head.txt');

	include('rfc822.php');
?>

<p class="homelink"><a href="/php/rfc822/">&laquo; Back to project homepage</a></p>

<h1>RFC 822 Demo</h1>

<p>Enter an email address to test:</p>

<form action="demo.php" method="get">
<input type="text" name="e" value="<?=HtmlSpecialChars($_GET['e'])?>" style="width: 400px" />
<input type="submit" value="Go" />
</form>

<? if (strlen($_GET['e'])){ ?>

<hr />

<p><b>Address:</b> <?=HtmlSpecialChars($_GET['e'])?></p>
<p><b>Result:</b>
<? if (is_valid_email_address($_GET['e'])){ ?>
	<span style="color: green">Valid</span>
<? }else{ ?>
	<span style="color: red">Invalid</span>
<? }?>
</p>

<? } ?>


<?
	include('../../foot.txt');
?>
