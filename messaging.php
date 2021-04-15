<?php

function dm($recipient, $text, $ig){
	$recipients = ['users' => [$ig->people->getUserIdForName($recipient)]];
	$ig->direct->sendText($recipients, $text);
}/*
function compose($ig){
	print_r($ig->direct->getInbox($ig->getOldestCursor()));
}
function getMessages($ig){
	$maxId = null;
    do {
        $response = $ig->direct->getInbox($maxId);
        $inbox = array_merge($followers, $response->getUsers());
        $maxId = $response->getNextMaxId();
    } while ($maxId !== null);
}*/
?>