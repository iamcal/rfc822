<?
	error_reporting(30719 | 2048); # E_ALL | E_STRICT
	ini_set("display_errors", 1);


	#
	# read the tests (PHP 4 compatible!)
	#

	$test = array();
	$tests = array();
	$glossary = array();
	$last_key = '?';
	$last_glossary_id = '?';
	
	$parser = xml_parser_create();

	function start($parser, $element_name, $element_attrs){
		$GLOBALS['last_key'] = StrToLower($element_name);

		if ($element_name == 'GLOSSARY'){
			$GLOBALS['last_glossary_id'] = $element_attrs['ID'];
		}
	}

	function stop($parser, $element_name){
		$GLOBALS['last_key'] = '?';
		if ($element_name == 'TEST'){
			$GLOBALS['tests'][$GLOBALS['test']['id']] = $GLOBALS['test'];
			$GLOBALS['test'] = array();
		}
		if ($element_name == 'TAG'){
			$GLOBALS['test']['tags'][] = $GLOBALS['test']['tag'];
			unset($GLOBALS['test']['tag']);
		}
		if ($element_name == 'GLOSSARY'){
			$GLOBALS['glossary'][$GLOBALS['last_glossary_id']] = $GLOBALS['test'];
			$GLOBALS['test'] = array();
		}
	}

	function char($parser, $chr){
		$chr = str_replace('\\0', "\0", $chr);
		if ($GLOBALS['last_key'] == 'tests') return;
		if ($GLOBALS['last_key'] == 'test') return;
		if ($GLOBALS['last_key'] == 'glossary') return;
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
	# perform some type conversion
	#

	foreach ($tests as $k => $v){

		$tests[$k]['valid'] = $tests[$k]['valid'] == 'true' ? true : false;
		$tests[$k]['warning'] = $tests[$k]['warning'] == 'true' ? true : false;
		$tests[$k]['id'] = intval($tests[$k]['id']);
	}

?>
