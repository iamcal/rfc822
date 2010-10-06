<?
	include('runner.php');

	$ran = 0;
	$passed = 0;

	foreach ($tests as $test){

		if ($test['id'] > 50) continue;

		$ran++;
		if ($test['expected'] == $test['result_3696']){
			$passed++;
		}else{
			print_r($test);
		}
	}

	echo "Passed $passed of $ran\n";
?>
