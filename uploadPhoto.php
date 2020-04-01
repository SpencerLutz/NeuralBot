<?php

function post($num, $captionText, $ig){

	$path = '/Users/student/Desktop/Neuralbot/Photos/';
	$filepath = glob($path.'image'.$num.'.*')[0];

	try {
		$photo = new \InstagramAPI\Media\Photo\InstagramPhoto($filepath);
		$ig->timeline->uploadPhoto($photo->getFile(), ['caption' => $captionText]);
		rename($filepath, $path.'Posted/'.substr($filepath, 40));
	} catch (\Exception $e) {
		echo 'Something went wrong: '.$e->getMessage()."\n";
	}
}

function getImages(&$num, $pnum, &$anum, &$acc, &$maxn, &$pmax, $ig){
	$userId = $ig->people->getUserIdForName($acc[$anum]);
	//number to be put on image, essentially
	//do {=
	
	for($i = 0; $i < $pnum; $i++){
		$response = $ig->timeline->getUserFeed($userId, $maxn[$anum]);
		if(is_null($maxn[$anum] = $response->getNextMaxId())){
			unset($acc[$anum]);
			unset($maxn[$anum]);
			$anum = rand(count($acc));
			return false;
		}
		foreach ($response->getItems() as $item) {
			if ($item->getMediaType() == \InstagramAPI\Response\Model\Item::PHOTO)
				saveImage($num, $n, $pmax, $anum, $acc, $item->getImageVersions2()->getCandidates()[0]->getUrl());
			//elseif ($item->getMediaType() == \InstagramAPI\Response\Model\Item::VIDEO)
				//saveImage($num, $n, $pmax, $anum, $acc, $item->getVideoVersions()[0]->getUrl(), '.mp4');
			elseif ($item->getMediaType() == \InstagramAPI\Response\Model\Item::ALBUM) {
				foreach ($item->getCarouselMedia() as $carouselMedia) {
					if ($carouselMedia->getMediaType() == \InstagramAPI\Response\Model\Item::PHOTO)
						saveImage($num, $n, $pmax, $anum, $acc, $carouselMedia->getImageVersions2()->getCandidates()[0]->getUrl());
					elseif ($carouselMedia->getMediaType() == \InstagramAPI\Response\Model\Item::VIDEO)
						saveImage($num, $n, $pmax, $anum, $acc, $carouselMedia->getVideoVersions()[0]->getUrl());
				}
			}
		}
		$maxn[$anum] = $response->getNextMaxId();
		sleep(5);
	}
	return true;
	//} while (!is_null($maxId = $response->getNextMaxId()));
}

function saveImage(&$num, &$n, &$pmax, $anum, $acc, $url, $ext = '.jpg'){
	$n = ($num*count($acc))+$anum;
	$path = '/Users/student/Desktop/Neuralbot/Photos/';
	$dir = $path.'image'.$n.$ext;
	file_put_contents($dir, file_get_contents($url));
	$num++; //something is incrementing way too much
	$pmax++; 
}
?>