<?php
if (!isset($_GET['playlist']))
	die('Bad Request');
$type = filter_input(INPUT_GET, 'type');
$plID = filter_input(INPUT_GET, 'playlist');

if (preg_match('/^[a-zA-Z0-9]+$/', $plID) == 0)
	die('Bad Request');
$playlist = array();

foreach (json_decode(file_get_contents('https://raw.githubusercontent.com/FighterX2500/JukeBoxMusic/master/playlist.json'),true) as $md5 => $fileInfo) {
	if(isset($fileInfo['playlists']) && in_array($plID, $fileInfo['playlists'])){
		$attr = new stdClass();
		$attr->title = isset($fileInfo['title']) ? $fileInfo['title'] : '';
		$attr->artist = isset($fileInfo['artist']) ? $fileInfo['artist'] : '';
		$attr->album = isset($fileInfo['album']) ? $fileInfo['album'] : '';
		if (isset($fileInfo['playtime_seconds']))
			$attr->length = (string) floor(floatval($fileInfo['playtime_seconds']) * 10);
		else
			$attr->length = "";
		$attr->url = 'https://raw.githubusercontent.com/FighterX2500/JukeBoxMusic/master/files/' . $md5;
		
		$playlist[] = $attr;
	}
}
header('Content-type: application/json');
$enc = json_encode($playlist, JSON_PRETTY_PRINT);
if (!$enc)
	echo json_last_error_msg();
echo $enc;