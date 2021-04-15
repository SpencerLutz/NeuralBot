<?php

include("uploadPhoto.php");
include("messaging.php");

set_time_limit(0);
date_default_timezone_set('Eastern');

require '/Users/student/vendor/autoload.php';

$ig = new \InstagramAPI\Instagram(false, false);

$save = file_get_contents('/Users/student/Desktop/Neuralbot/save.txt');

$username = 'neuralbot';
$password = 'REDACTED';
$pmax = 0;//max number on photos
$post = 0;//number of photo to be posted
$num = 0;//number on photo
$acc = ['juicybignut', 'threak'];//array of accounts to be used
$max = [null, null];//array of maxIds for the accounts
accn = [0, 0];//total number of posts from that account posted

try {
    $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}
register_shutdown_function('save');
while(true){
	if($post >= $pmax){
		for($i = 0; $i < count($acc); $i++) getImg($accn, $i, $acc, $max, $pmax, $ig);
	} 
	post($post, "", $ig); //date('D, d M Y H:i:s')
	$post++;
	sleep(3600);
	//dm('threadspools', 'Follow me!', $ig);
}
function save(){
	$xacc = json_encode($acc)." ";
	$xmax = json_encode($max)." ";
	$xacn = json_encode($accn);
	$str = $pmax." ".$pmax." ".$pmax." ".$xacc.$xmax.$xacn;
	file_put_contents('/Users/student/Desktop/Neuralbot/save.txt', $str);

}

function getImg(&$accn, &$anum, &$acc, &$max, &$pmax, $ig){//arregla necesitan & pq iba todos
	if(!getImages($accn[$anum], 1, $anum, $acc, $max, $pmax, $ig)){
		getImg($accn, $anum, $acc, $max, $pmax, $ig);
	}
}
?>
