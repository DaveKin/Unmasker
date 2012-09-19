<?php


class unmasker{


  protected $con;
  protected $db_selected;
  protected $twitterup = false;

  
public function __construct(){
  global $con, $db_selected, $twitterup;
  $con = mysql_connect("internal-db.s53401.gridserver.com","db53401","ykOsJhav");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }
  $db_selected = mysql_select_db("db53401_unmasker", $con);
  if (!$db_selected){
    die ("Can\'t use unmasker database : " . mysql_error());
  }
  $twitterup = $this->testTwitter($t);
}
  
public function getDemons(){
  $demons = mysql_query("SELECT * FROM  unmasklog WHERE Score < 0 ORDER BY  Score ASC , Count DESC LIMIT 0 , 10");
  $results = array();
  while($row = mysql_fetch_array($demons, MYSQL_ASSOC)){
    array_push($results,$row);
  }
  return $results;
  /*
  for ($i = 0 ; $i < 5 && ($result_rows[] = mysql_fetch_array($qdemons)); $i++){
  $demons = array_reverse($result_rows);
  }
  */
}
public function getAngels(){
  $angels = mysql_query("SELECT * FROM  unmasklog WHERE Score > 0 ORDER BY  Score DESC , Count DESC LIMIT 0 , 10");
  $results = array();
  while($row = mysql_fetch_array($angels, MYSQL_ASSOC)){
    array_push($results,$row);
  }
  return $results;
}
public function getRecent(){
  $recent = mysql_query("SELECT * FROM  unmasklog ORDER BY  DateChecked DESC LIMIT 0 , 10");
  $results = array();
  while($row = mysql_fetch_array($recent, MYSQL_ASSOC)){
    array_push($results,$row);
  }
  return $results;
}
public function getPopular(){
  $popular = mysql_query("SELECT * FROM  unmasklog ORDER BY Count DESC LIMIT 0 , 10");
  $results = array();
  while($row = mysql_fetch_array($popular, MYSQL_ASSOC)){
    array_push($results,$row);
  }
  return $results;
}


public function doit($user){
  global $twitterup;
  $umr = new unmasker_response();
  if($user!=null && $user!=""){
    //input submitted
    $selectSQL = sprintf("SELECT * FROM unmasklog WHERE UserName = \"%s\"",mysql_real_escape_string($user));
    $dbuser = mysql_query($selectSQL);
    if(mysql_num_rows($dbuser)){
      //echo "<p>user found in db</p>";
    	if(!$twitterup){
    		//generate from db
    		$row = mysql_fetch_array($dbuser);
    		//$result = $this->outputBio($row['Bio'],$row['ProfileImage']);
        $umr->username = $row['UserName'];
        $umr->realname = $row['RealName'];
        $umr->imgurl = $row['ProfileImage'];
        $umr->bio = $row['Bio'];
        $umr->translated = $this->translateBio($row['Bio']);
        $umr->action = "";
        $umr->getAction();
        if($umr->action == "ascend"){
          $updateSQL = "UPDATE unmasklog SET DateChecked=NOW(), Count = Count + 1, Score = Score + 1 WHERE UID = ".$row['UID'];
        }else{
          $updateSQL = "UPDATE unmasklog SET DateChecked=NOW(), Count = Count + 1, Score = Score - 1 WHERE UID = ".$row['UID'];
        }
        //echo "<pre>".$updateSQL."</pre>";
        $update = mysql_query($updateSQL); 
    	}else{
        $udata = $this->getTwitterUser($user);
        if($udata->data->error=="Not found"){
        	 $umr->message = "Are you sure that's a real Twitter username?";
        	 $umr->action = "fail";
        }else{
          $umr->username = $udata->data->screen_name;
          $umr->realname = $udata->data->name;
          $umr->imgurl = $udata->data->profile_image_url;
          $umr->bio = $udata->data->description;
          $umr->translated = $this->translateBio($udata->data->description);
          $umr->action = "";
          $umr->getAction();
          if($umr->action == "ascend"){
            $updateSQL = "UPDATE unmasklog SET DateChecked=NOW(), RealName='".mysql_real_escape_string($umr->realname)."', ProfileImage='".mysql_real_escape_string($umr->imgurl)."', Bio = '".mysql_real_escape_string($umr->bio)."', Count = Count + 1, Score = Score + 1 WHERE UID = ".$udata->data->id;
          }else{
            $updateSQL = "UPDATE unmasklog SET DateChecked=NOW(), RealName='".mysql_real_escape_string($umr->realname)."', ProfileImage='".mysql_real_escape_string($umr->imgurl)."', Bio = '".mysql_real_escape_string($umr->bio)."', Count = Count + 1, Score = Score - 1 WHERE UID = ".$udata->data->id;
          }
          //echo "<pre>".$updateSQL."</pre>";
          $update = mysql_query($updateSQL); 
        }
      }
    }else{
      //if not found in db
      //echo "<p>user not found in db</p>";
    	if(!$twitterup){
    		$umr->message = "Oh NOES!, Twitter is not responding";
    		$umr->action = "fail";
    	}else{
        //get from twitter
        $udata = $this->getTwitterUser($user);
        if($udata->data->error=="Not found"){
      		$umr->message = "Are you sure that's a real Twitter username?";
      		$umr->action = "fail";
        }else{
          $umr->username = $udata->data->screen_name;
          $umr->realname = $udata->data->name;
          $umr->imgurl = $udata->data->profile_image_url;
          $umr->bio = $udata->data->description;
          $umr->translated = $this->translateBio($udata->data->description);
          $umr->action = "";
          $umr->getAction();
          $damned = array(" was sent to Hell ",
              " booked a place in Twitter Hell ",
              " was damned to Twitter Hell ",
              " was judged unworthy ",
              " eats buzzword BS for breakfast ",
              " gets the basement apartment ",
              " dropped like a stone ",
              " is in a warm place ",
              " is getting toasted ",
              " likes it crispy ",
              " wishes there was air-con ",
              " smells like barbeque ");
          $deified = array(" received a Twitter halo ",
              " booked a place in Twitter Heaven ",
              " is pure and angelic ",
              " was judged worthy ",
              " is free from buzzword sin ",
              " has a penthouse reservation ",
              " soars with the angels ",
              " has a cloud to sit on ",
              " will be getting harp lessons ",
              " exudes goodness ",
              " is just so awesome, it's not even funny ",
              " is fragrant and lovely ",
              " makes my tummy feel all fizzy ");
          if($umr->action == "ascend"){
            $score=1;
            $this->postTweet("@".$umr->username.$this->getRandomString($deified).$this->get_tiny_url("http://unmasker.webdeveloper2.com/".$umr->username)." #unmasked");
          }else{
            $score=-1;
            $this->postTweet("@".$umr->username.$this->getRandomString($damned).$this->get_tiny_url("http://unmasker.webdeveloper2.com/".$umr->username)." #unmasked");
          }
          $insertSQL = sprintf("INSERT INTO unmasklog (UID,UserName,RealName,DateChecked,Count,Score,ProfileImage,Bio)VALUES (%s,\"%s\",\"%s\",NOW( ),1,%s,\"%s\",\"%s\")",
          $udata->data->id,
          mysql_real_escape_string($udata->data->screen_name),
          mysql_real_escape_string($udata->data->name),
          $score,
          mysql_real_escape_string($udata->data->profile_image_url),
          mysql_real_escape_string($udata->data->description));

          //echo "<pre>".$insertSQL."</pre>";
          $insert = mysql_query($insertSQL);
        }
      }
    }
  }
  return $umr;
}

function testTwitter($t=""){
  $test = $this->sendRequest("http://twitter.com/help/test.json");
  //print_r($test);
  if($test->data == "ok"){
    return true;
  }else{
    return false;
  }
}

function getTwitterUser($TwUserName){
    $response = $this->sendRequest("http://twitter.com/users/show/{$TwUserName}.json", true);  
    //print_r($response);
    return $response;
}

function postTweet($Status){
    $data = "status=".$Status;
    $response = $this->sendRequest("http://twitter.com/statuses/update.json", true, "POST", $data);  
    //print_r($response);
    return $response;
}

function getRandomString($stringArray){
    srand ((double) microtime( )*1000000);
    $rand_keys = array_rand($stringArray, 1);
    return $stringArray[$rand_keys];
}

function translateBio($bio){
$written = array("expert",
  "social media",
  "consultant",
  "evangelist",
  "guru",
  "marketing",
  "marketer",
  " pr ",
  "passionate",
  "strumpette",
  "entrepreneur",
  "enthusiast",
  "enthusiasm",
  "loosehead prop",
  "social",
  "visionary",
  "web 2.0",
  "web2.0",
  "advertising",
  "all round good guy",
  "genius",
  "thought leader",
  "where the future is being made today",
  "recruiter",
  "recruitment",
  "problem solver"
  );
$meaning = array("charlatan",
  "bandwagon-jumping",
  "waste-of-space",
  "blow-hard",
  "class a drug abuser",
  "bullshit",
  "bullshitter",
  " shouting ",
  "disingenuous",
  "cunt",
  "chancer",
  "wanker",
  "masturbation",
  "complete cunt",
  "buzzword",
  "gobshite",
  "blah blah blah",
  "blah blah blah",
  "meaningless noise making",
  "cunt",
  "heavy drinker",
  "delusional drunk",
  "where the future is being plagiarised today",
  "manhunter",
  "human-trafficking",
  "jigsaw fetishist"
  );
  $nobio = array("I'm too lazy to write a bio",
  "I'm nobody",
  "Don't look at me, I'm irrelevant",
  "I have nothing to say",
  "If you don't already know who I am, you're not important enough for me to explain",
  "I don't respect you enough to write a bio",
  "I don't respect myself enough to write a bio",
  "I'm two of the following: mysterious, lazy, twat",
  "I'm trying to seem enigmatic",
  "Unable to write a bio, the truth is too painful"
  );

  if(trim($bio)==""){
    return $this->getRandomString($nobio);
  }else{
    $newbio = str_replace($written, $meaning, strtolower($bio));
    return ucwords($newbio);
  }
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

  /**
  * create and excute a CURL request
  * @ignore  
  */
  private function sendRequest($uri, $auth=false, $method ='GET', $data ='')
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    if($auth)
    {
        curl_setopt($ch, CURLOPT_USERPWD, "unmasker:numberwang");
    }
    if('POST' == ($method = strtoupper($method)))
    {
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    else if('GET' != $method)
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 21600);

    $data = curl_exec($ch);
    $meta = curl_getinfo($ch);

    curl_close($ch);

    return new c_response($data, $meta);
  }
}


class unmasker_response{
  public $username = "";
  public $realname = "";
  public $imgurl = "";
  public $bio = "";
  public $translated = "";
  public $message = "";
  public $action = "";
  public $avatarcomment = "";

  function getAction(){
    $specialBio = $this->getSpecialBio($this->username);
    if($specialBio){
      $this->translated = $specialBio;
    }
    if($this->action==""){
      $angelic = array("You're just perfect, go straight to Heaven, DO pass GO, DO collect 200 quid",
      "Well done, you evaded the bullshit sniffers ... for now",
      "You came up clean, fear not, I'll keep digging",
      "You win this time, but I'll be watching you.",
      "Your bio looks clean, it must be some sort of trick so I'll look into this abberation. For now you may have a probationary halo."
      );
      $demonic = array("Back so soon?",
      "Welcome home, we've missed you",
      "Aha, I've been expecting you",
      "What were you expecting, a prize?",
      "Make yourself at home, you'll be here a while"
      );
      $specialAction = $this->getSpecialAction($this->username);
      if($specialAction==false){
        if(strtolower($this->bio) == strtolower($this->translated)){
          $this->action = "ascend";
          $this->avatarcomment = $this->getRandomString($angelic);
        }else{
          $this->action = "descend";
          $this->avatarcomment = $this->getRandomString($demonic);
        }
      }else{
        if($specialAction=="ascend"){
          $this->action = "ascend";
          $this->avatarcomment = $this->getRandomString($angelic);
        }
        if($specialAction=="descend"){
          $this->action = "descend";
          $this->avatarcomment = $this->getRandomString($demonic);
        }
      }
    }
    return $this;
  }
  function getSpecialBio($username){
    $specialpeople = array(
      "gezd" => "pesters Unmasker for the secret sauce recipe",
      "jasoncrouch" => "has no sense of humour",
      "rufus_jay" => "Actually pretty awesome ... actually",
      "billboorman" => "cracking the unmasker code, like a digital Robert Langdon",
      "richard_dawkins" => "Secretly prays to God for forgiveness, rarely gets a reply"
    );
    if (array_key_exists(strtolower($username), $specialpeople)) {
      return $specialpeople[strtolower($username)];
    }else{
      return false;
    }
  }
  function getSpecialAction($username){
    $specialactions = array(
      "gezd" => "descend",
      "jasoncrouch" => "descend",
      "rufus_jay" => "ascend",
      "billboorman" => "ascend"
    );
    if (array_key_exists(strtolower($username), $specialactions)) {
      return $specialactions[strtolower($username)];
    }else{
      return false;
    }
  }
  function getRandomString($stringArray){
      srand ((double) microtime( )*1000000);
      $rand_keys = array_rand($stringArray, 1);
      return $stringArray[$rand_keys];
  }

}



/**
 * Response class
 * Returns both the response data and the curl request info to aid debugging
 * @package contextvoice
 * @subpackage Response
 */
class c_Response{
  /**
   * an object derived from the output of the API call
   */  
  public $data;

  /**
   * an object derived from the curl_getinfo method, provides information useful for handling network errors or API unavailability 
   */  
  public $info;

  /**
   * Constructor, only used by Main class  
   * @ignore
   */     
  public function __construct($inData,$inInfo){
    $this->data = json_decode($inData);
    $this->info = $inInfo;
  }
}

?>
