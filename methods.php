<?php

set_time_limit(0);
date_default_timezone_set('America/New_York');

function getC($ig, $mediaId){
	$cr = array(); $see = 0;
	$maxId = null;
	do {
		$comments = $ig->media->getComments($mediaId, ['max_id' => $maxId]);
		foreach($comments->getComments() as $v){
			array_push($cr, $v->getText()); $see++;
			$ig->media->deleteComment($mediaId, $v->getPk());
		}
		$maxId = $comments->getNextMaxId();
	} while ($maxId !== null);
	printf("Scanned %s comments.\n", $see);
	return $cr;
}
function getImgs($ig){
	$response = $ig->timeline->getUserFeed($ig->people->getUserIdForName('gooeygreen'), null);
	foreach($response->getItems() as $item){
		$mediaId = $item->getId(); $down = 0;
		$c = getC($ig, $mediaId);
		$rankToken = \InstagramAPI\Signatures::generateUUID();
		foreach($c as $v){
			$tagfeed = $ig->hashtag->getFeed($v, $rankToken, null)->getItems();
			getInd($tagfeed, $down, $v);
		}
	}
	printf("Downloaded %s images.\n", $down);
}
function saveImage($url, $ext = '.jpg'){
	$path = '/Users/student/Desktop/Neuralbot/Photos/';
	$ptff = $path.scandir($path, SCANDIR_SORT_DESCENDING)[0];
	$n = (int)(pathinfo($ptff)['filename'])+1;
	$dir = $path.$n.$ext;
	file_put_contents($dir, file_get_contents($url));
}
function getInd($tagfeed, &$down, $v, &$ncount = 0){
	$index = rand(0, count($tagfeed)-1);
	$img = $tagfeed[$index];
	if($img == null){
		if($ncount<count($tagfeed)){
			$ncount++;
			echo "Null Image\n";
			getInd($tagfeed, $down, $v, $ncount);
		} else {
			printf("No image found for %s.\n", $v);
		}
	}
	if($img !== null){
		if($img->getMediaType() == \InstagramAPI\Response\Model\Item::PHOTO){
			saveImage($img->getImageVersions2()->getCandidates()[0]->getUrl()); 
			$down++; return;
		}
		if($img->getMediaType() == \InstagramAPI\Response\Model\Item::ALBUM)
			foreach ($img->getCarouselMedia() as $carouselMedia)
				if ($carouselMedia->getMediaType() == \InstagramAPI\Response\Model\Item::PHOTO){
					print($img->getImageVersions2());
					saveImage($img->getImageVersions2()->getCandidates()[0]->getUrl()); 
					$down++; return;
				}
		if($img->getMediaType() == \InstagramAPI\Response\Model\Item::ALBUM){
			echo "Video Error\n";
			getInd($tagfeed, $down, $v);
		}
	}
}
function post($captionText, $ig){
	$opath = '/Users/student/Desktop/Neuralbot/';
	$path = $opath.'Photos/';
	$filepath = $path.scandir($path)[3];
	try {
		$photo = new \InstagramAPI\Media\Photo\InstagramPhoto($filepath);
		$ig->timeline->uploadPhoto($photo->getFile(), ['caption' => $captionText]);
		rename($filepath, $opath.'Posted/'.pathinfo($filepath)['filename']);
		print("Posted ".date('h:i')."\n");
	} catch (\Exception $e) {
		echo "No photos in queue.\n";
		//echo 'Something went wrong: '.$e->getMessage()."\n";
	}
}
function getCaption(){
	return "I'm a robot! Comment a word on this post and I'll post something related! Or, you can dm me a photo to post! Tell your friends about me too so they can join the fun!";
}
?>