<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
require_once('class_unmasker.php');
if(isset($_REQUEST['user'])){
  $unmasker = new unmasker();
  $data = $unmasker->doit($_REQUEST['user']);
  echo json_encode($data);
}


if(isset($_REQUEST['get'])){
  $unmasker = new unmasker();
  switch($_REQUEST['get']){
    case "demons":
      $data = $unmasker->getDemons();
      break;
    case "angels":
      $data = $unmasker->getAngels();
      break;
    case "recent":
      $data = $unmasker->getRecent();
      break;
    case "popular":
      $data = $unmasker->getpopular();
      break;
  }
  if(isset($_REQUEST['format'])){
    if($_REQUEST['format']=="html"){
      //print_r($data);
      echo "<ul>";
      foreach($data as $person){
        echo "<li>";
        echo "<a href=\"http://twitter.com/".$person['UserName']."\"><img src=\"".$person['ProfileImage']."\" /></a>";
        echo "<a href=\"http://twitter.com/".$person['UserName']."\" target=\"twitter\">".$person['RealName']." (@".$person['UserName'].")</a>";
        echo "<p><a href=\"/".$person['UserName']."\">".$person['Bio']."</a></p>";
        echo "</li>";
      }
      echo "</ul>";
    }else{
      echo json_encode($data);
    }  
  }else{
    echo json_encode($data);
  }
}
if(isset($_REQUEST['shorturl'])){
  echo get_tiny_url($_REQUEST['shorturl']);
}


// thanks to David Walsh http://davidwalsh.name/create-tiny-url-php
function get_tiny_url($url)  
{  
  $ch = curl_init();  
	$timeout = 5;  
	curl_setopt($ch,CURLOPT_URL,'http://is.gd/api.php?longurl='.$url);  
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
	$data = curl_exec($ch);  
	curl_close($ch);  
	return $data;
}

?>
