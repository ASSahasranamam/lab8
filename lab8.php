<?php
#documentation - http://googlecloudplatform.github.io/google-cloud-php/#/docs/v0.22.0/storage/storageobject?method=delete
require 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;
define ("PROJECTID",'');
define ("BUCKETNAME",'');
putenv('GOOGLE_APPLICATION_CREDENTIALS=cred.json');
$storage = new StorageClient([ 'projectId' => PROJECTID ]);
$bucket = $storage->bucket(BUCKETNAME);
function post($request, $bucket) {
  if (isset($request[0]) && isset($request[1])) {
    $filename=$request[1];
	$filename=$filename . ".txt";
    $object = $bucket->object($filename);
    if ($object->exists()) {
      echo "Object already exists!";
      http_response_code(404);
    }
    else { 
      $file=fopen($filename,'w');
      fwrite($file,"username: $request[0], firstname: $request[1], lastname: $request[2]");
      fclose($file);
      $result=$bucket->upload(fopen($filename,'r'));
      echo json_encode($result->info());
    }
  }
  else {
    echo "You need to enter values for the  username, firstname and lastname";
    http_response_code(404);
  } 
}
function get($request, $bucket) {
  $filename=$request[1];
  $filename=$filename . ".txt";
  $object = $bucket->object($filename);
  if ($object->exists()) {
    $content=$object->downloadAsString();
    $replace=array("username: ", " firstname: ", "lastname: ");
  //$object->downloadToFile('file_backup.txt');
    list($arr['username'], $arr['firstname'], $arr[lastname])=explode(",", str_replace($replace,"",$content));
    echo json_encode($arr); 
  }
  else {
    echo "Object doesn't exist!";
    http_response_code(404);
  }
}
function put($request, $bucket) {
  $filename=$request[1];
  $filename=$filename. ".txt";
  $object = $bucket->object($filename);
  if ($object->exists()) {
    post($request,$bucket);
  }  
  else { 
    echo "Object doesn't exist!";
    http_response_code(404);
  }
}
function deleter($request, $bucket){
  $filename=$request[1];
  $filename=$filename. ".txt";
  $object = $bucket->object($filename);
  if ($object->exists()) {
    $object->delete();
    echo json_encode(array("file"=>$filename, "action"=>"deleted"));
  }
  else {
    echo "Object doesn't exist!";
    http_response_code(404);
  }
}
$method = $_SERVER['REQUEST_METHOD'];
<?php

define('DB_SERVER', '104.154.161.94');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'asdzxc123');
define('DB_DATABASE', 'personalinfo');
$mysqli =  mysqli_connect('104.154.161.94', 'root', 'asdzxc123','personalinfo');
$table="data";
$check=2;

if ($mysqli->connect_errno) {
	

    echo "Error: Failed to make a MySQL connection, here is why: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
}

function check($result,$sql){
	if (!$result = $mysqli->query($sql)) {
		echo "Sorry, the website is experiencing problems.";
		echo "Error: Our query failed to execute and here is why: \n";
		echo "Query: " . $sql . "\n";
		echo "Errno: " . $mysqli->errno . "\n";
		echo "Error: " . $mysqli->error . "\n";
		exit;
	}
	if ($result->num_rows === 0) {
	
		$check=0;
		exit;
	}

}

function getall(){
	echo "hear";
	$sql = "SELECT * FROM $table";
	check($result, $sql);
	if ($check === 0) {
		echo "Db is empty";
		exit;
		
	}else{
		$arresult = $result->->fetch_array(MYSQLI_NUM);
		echo $arresult;
		exit;
	}

}


function post($request) {
	$sql = "SELECT * FROM $table WHERE email = $request[3]";
	check($result, $sql);
	if ($check === 0) {
	
		$sql = "insert into $table (firstname, lastname, email, age, zip) values ($request[1],$request[2],$request[3],$request[4],$request[5]";
		$mysqli->query($sql);
		exit;
	}else{
		echo "An entry already exists under that email";
	}
}
function get($request) {
	$sql = "SELECT * FROM person WHERE id = $request[0]";

	check($result, $sql);
	if ($check === 0) {
	
		echo "Sorry, that entry does not exist in this database";
		exit;
	}else{
		echo "$result";
		}
	}
	
function put($request, $bucket) {
	$sql = "SELECT * FROM $table WHERE id = $id[0]";
	$result = $mysqli->query($sql)
	if ($result->num_rows > 0) {
		$sql = "UPDATE $table set firstname = $request[1], lastname = $request[2],  age = $request[3], email = $request[4], zipcode = $request[5]";
		exit;
}
}
function deleter($request){
$sql = "SELECT * FROM $table WHERE id = $request[0]";

	$check($result,$sql)	
	if ($check === 0) {
	
		echo "Sorry, that entry does not exist in this database"
		exit;
	}else{
		$sql = "delete FROM person WHERE firstname = $request[0]";
		echo "That entry was deleted";
		exit();
		}
	}
}
$result->free();
$mysqli->close();
?>
  $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
  switch ($method) {
    case 'GET':
      get($request, $bucket); break;
    case 'PUT':
      put($request, $bucket); break;
    case 'POST':
      post($request, $bucket); break;
    case 'DELETE':
      deleter($request, $bucket); break;
  }
}
else {
  echo "Nothing here, use http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'/username/firstname/lastname to use the API';
}
?>
