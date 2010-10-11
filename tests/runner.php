<?
	error_reporting(30719 | 2048); # E_ALL | E_STRICT
	ini_set("display_errors", 1);

	include('../rfc3696.php');


	#
	# read the tests (PHP 4 compatible!)
	#

	$test = array();
	$tests = array();
	$last_key = '?';
	
	$parser = xml_parser_create();

	function start($parser, $element_name, $element_attrs){
		$GLOBALS['last_key'] = StrToLower($element_name);
	}

	function stop($parser, $element_name){
		$GLOBALS['last_key'] = '?';
		if ($element_name == 'TEST'){
			$GLOBALS['tests'][$GLOBALS['test']['id']] = $GLOBALS['test'];
			$GLOBALS['test'] = array();
		}
	}

	function char($parser, $chr){
		$chr = str_replace('\\0', "\0", $chr);
		if ($GLOBALS['last_key'] == 'tests') return;
		if ($GLOBALS['last_key'] == 'test') return;
		if ($GLOBALS['last_key'] == '?') return;

		if (isset($GLOBALS['test'][$GLOBALS['last_key']])){

			$GLOBALS['test'][$GLOBALS['last_key']] .= $chr;
		}else{
			$GLOBALS['test'][$GLOBALS['last_key']] = $chr;
		}
	}

	xml_set_element_handler($parser, "start", "stop");
	xml_set_character_data_handler($parser, "char");
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

	$fp = fopen("tests.xml", "r");

	while ($data = fread($fp, 4096)){
		xml_parse($parser, $data, feof($fp)) or die (sprintf("XML Error: %s at line %d", xml_error_string(xml_get_error_code($parser)), xml_get_current_line_number($parser)));
	}
	xml_parser_free($parser);


	#
	# run the tests
	#

	$totals = array(
		'all'	=> 0,
		'public' => 0,
		'strict' => 0,
	);

	foreach ($tests as $k => $v){

		$tests[$k]['expected'] = ($v['valid'] == 'true') ? 1 : 0;
		$tests[$k]['result_public'] = is_valid_email_address($v['address']) ? 1 : 0;
		$tests[$k]['result_strict'] = is_valid_email_address($v['address'], array('public_internet' => false)) ? 1 : 0;

		$totals['all']++;
		$totals['public'] += ($tests[$k]['result_public'] == $tests[$k]['expected']) ? 1 : 0;
		$totals['strict'] += ($tests[$k]['result_strict'] == $tests[$k]['expected']) ? 1 : 0;

		unset($tests[$k]['valid']);
	}

	function is_valid($x){
		return $x ? 'Valid' : 'Invalid';
	}

	function show_escapes($s){
		return str_replace(array("\r","\n"," ","\0"), array("&amp;#13;","&amp;#10;","&nbsp;","&amp;#0;"), $s);
	}
?>
