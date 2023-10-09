<?php
include './autoload.php';
$db=new DataBase('DBNAME','HOST','USERNAME','PASSWORD');

function Authenticate($headers=[]) {
  $key=null;
  if(array_key_exists('key', $headers)) {
    $key=$headers['key'];
  }
  if($key == 'KEYVALUE') {
    return 1;
  }
  return 0;
}

function returnErrorResponse() {
  echo json_encode(array("status"=>0));
  http_response_code(401);
}

if(isset($_GET['upload']) && $_GET['upload'] == 1) {
  $response=$db->getAllUsers();
  $isAuth = Authenticate(getallheaders());
  if($isAuth) {
    echo json_encode(array("status"=>1, "data"=>$response));
  } else {
    returnErrorResponse();
  }
}

if(isset($_GET['users']) && $_GET['users'] == 1) {
  header('Content-Type: application/json');
  $response=$db->getAllUsers();
  echo json_encode(array("status"=>1, "data"=>$response));
}

if(isset($_GET['update']) && $_GET['update'] == 1) {
  header('Content-Type: application/json');
  if(Authenticate(getallheaders())) {
    if($db->updateUser($_POST['data'])){
      echo json_encode(array("status"=>1));
    } else{
      echo json_encode(array("status"=>0));
      }
    } else {
      returnErrorResponse();
    }
}

if(isset($_GET['delete']) && $_GET['delete'] == 1){
  if(Authenticate(getallheaders())) {
      header("Content-Type: application/json");
      if($db->deleteUser($_POST['id'])) {
        echo json_encode(array("status" => 1));
      } else {
        echo json_encode(array("status" => 0));
      }
  }
  else {
  returnErrorResponse();
  }
}

?>
