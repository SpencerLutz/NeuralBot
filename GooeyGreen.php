<?php

include("methods.php");

set_time_limit(0);
date_default_timezone_set('America/New_York');

require '/Users/student/vendor/autoload.php';

$ig = new \InstagramAPI\Instagram(false, false);

$username = 'gooeygreen';
$password = 'REDACTED';

try {
	$ig->login($username, $password);
} catch (\Exception $e) {
	echo 'Something went wrong: '.$e->getMessage()."\n";
	exit(0);
}
while(true){
	echo "Scanning... ";
	getImgs($ig);
	post(getCaption(), $ig);
	sleep(60);
}
?>
