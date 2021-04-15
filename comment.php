<?php

set_time_limit(0);
date_default_timezone_set('Eastern');

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

try {
    $userId = $ig->people->getUserIdForName('threak');
    $maxId = null;
    do {
        $response = $ig->timeline->getUserFeed($userId, $maxId);
        foreach ($response->getItems() as $item) {
            $ig->media->comment($item->getId(), "Great meme! Also, I'm just a meme stealing robot and I need some help growing. Thanks! Wanna drop a follow?");
            sleep(960);
        }
        $maxId = $response->getNextMaxId();
    } while ($maxId !== null); // Must use "!==" for comparison instead of "!=".
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}

