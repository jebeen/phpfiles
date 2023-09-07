<?php
   include './view.php';
   $user = new View();
     if($_SERVER['REQUEST_METHOD'] == 'POST') {
        extract($_POST);
        if(isset($edit)) {
         $user->setData();
         $user->editUser($name,$id,$age);
        }
        else {
         $user->setData();
         $id=$user->getCount();
         $data=array(
            "name" => $name,
            "id" => ++$id,
            "age" => $age
        );
        $user->addUser($data);
        }
        header('location: /movierating/api/view');
     }
     else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
        display($_GET['id']);
        if(isset($_GET['delete'])) {
         $user->setData();
         $user->deleteUser($_GET['id']);
         header('location: /movierating/api/view');
        }
     } else {
      $user->setData();
      $data = json_decode($user->getData());
      $ids = [];
      foreach ($data as $key => $value) {
         $p = substr($data[$key]->name, 0, strlen($_GET['text']));
         if($p == $_GET['text']) {
            array_push($ids, $data[$key]);
         }
      }
      display('',$ids);
     }
?>
