<?php
include('../twoauth/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

require("vars.php");

$connection = new TwitterOAuth($tw_consumer_key, $tw_consumer_secret, $tw_user_token, $tw_user_secret);

include("make_image.php");

// hae sivu, (with headers)
function getPage($url){
  $headers = [
      'Cache-Control: no-cache',
      'Whoami: Twitter-bot @hs_muutokset - tekijä @duukkis',
  ];
  $opts = [
      "http" => [
          "method" => "GET",
          "header" => implode("\r\n", $headers)
      ]
  ];
  $context = stream_context_create($opts);
  $response = file_get_contents($url, false, $context);
  if($http_response_header[0] == "HTTP/1.1 401"){
    return null;
  }
  return $response;
}

$urls = array(
    "http://www.hs.fi/rss/kotimaa.xml",
    "http://www.hs.fi/rss/ulkomaat.xml",
    "http://www.hs.fi/rss/talous.xml",
    "http://www.hs.fi/rss/politiikka.xml",
    "http://www.hs.fi/rss/kaupunki.xml",
    "http://www.hs.fi/rss/urheilu.xml",
    "http://www.hs.fi/rss/kulttuuri.xml",   
    "http://www.hs.fi/rss/paakirjoitukset.xml",   
    "http://www.hs.fi/rss/lastenuutiset.xml",   
    "http://www.hs.fi/rss/ruoka.xml",   
    "http://www.hs.fi/rss/koti.xml",   
    "http://www.hs.fi/rss/elamahyvinvointi.xml",   
    "http://www.hs.fi/rss/ura.xml",   
    "http://www.hs.fi/rss/autotiede.xml",   
    "http://www.hs.fi/rss/matka.xml",   
    "http://www.hs.fi/rss/historia.xml",
);

// write stuff into local file
$local_storage ="news.txt";

$existing = array();
$news = @file_get_contents($local_storage);
if(!empty($news)){
  $existing = unserialize($news);
}

$cc = 0;

// remove some bad characters (that are written into this bda.txt file) from headlines
function fix($text1){
  $bad_char = file_get_contents("bda.txt");
  $text1 = str_replace($bad_char, "", $text1);
  return $text1;
}

foreach($urls AS $k => $u){
  $f = getPage($u);
  if(!empty($f)){
    $rss = simplexml_load_string($f, "SimpleXMLElement", LIBXML_NOCDATA);
    // loop items
    foreach ($rss->channel->item as $item) {
      $guid = (string) $item->guid;
      $title = (string) $item->title;
      // item has changed significantly
      if (isset($existing[$guid]) && $existing[$guid] != $title && $cc < 2 && levenshtein($existing[$guid], $title) > 20) {
        $cc++;
        writeToFile(fix($existing[$guid]), fix($title));
        $media1 = $connection->upload('media/upload', ['media' => "otsikko.jpg"]);
        $parameters = [
          'status' => "",
          'media_ids' => implode(',', [$media1->media_id_string])
        ];
        $code = $connection->post('statuses/update', $parameters);
        // debug 
        // file_put_contents("changed.txt", "OLD ".$existing[$guid]." NEW ".$title." : ".levenshtein($existing[$guid], $title).PHP_EOL, FILE_APPEND);
      }
      $existing[$guid] = $title;
    }
  }
  // be polite dont bombard 
  sleep(1);
}

// write last 1000 headlines into local storage for comparison
krsort($existing);
$existing = array_slice($existing, 0, 1000, TRUE);
file_put_contents($local_storage, serialize($existing));
