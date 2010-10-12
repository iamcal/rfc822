<?
	include('reader.php');
	include('../rfc822.php');

	#
	# run the tests
	#

	$totals = array(
		'all'	=> 0,
		'public' => 0,
		'strict' => 0,
	);

	foreach ($tests as $k => $v){

		$tests[$k]['expected'] = $v['valid'] ? 1 : 0;
		$tests[$k]['result_public'] = is_valid_email_address($v['address']) ? 1 : 0;
		$tests[$k]['result_strict'] = is_valid_email_address($v['address'], array('public_internet' => false)) ? 1 : 0;

		$totals['all']++;
		$totals['public'] += ($tests[$k]['result_public'] == $tests[$k]['expected']) ? 1 : 0;
		$totals['strict'] += ($tests[$k]['result_strict'] == $tests[$k]['expected']) ? 1 : 0;
	}

	function is_valid($x){
		return $x ? 'Valid' : 'Invalid';
	}

	function show_escapes($s){
		return str_replace(array("\r","\n"," ","\0"), array("&amp;#13;","&amp;#10;","&nbsp;","&amp;#0;"), $s);
	}
?>
