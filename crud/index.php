<html>
<head>
<script src="../../bootstrap/js/jquery.min.js"></script>
<script src="./assets/crud.js" type="text/javascript"></script>
</head>
<body onLoad="getCity();getUsers()">
<?php
  $errMsg='';
  $statusMsg = '';

  include './autoload.php';
  $db=new DataBase('DBNAME','HOST','USERNAME','PASSWORD');
  $errors=array();
  if(!empty($_POST) && !isset($_GET['users'])) {
    extract($_POST);
    if($name != '') {
      if(strlen($name) == 1) {
        array_push($errors, "Name should not be initial only");
      }
    } else {
      array_push($errors, "Name should not be empty");
    }

    if(strlen($email) == 0) {
      array_push($errors, "Email should not be empty");
    }  else {
      $pattern="/^([a-zA-Z0-9]+)\@([a-z]+)\.([a-z]+)$/";
      if(!preg_match($pattern, $email)) {
        array_push($errors, "Invalid Email");
      }
    }

    if(count($errors)) {
      foreach($errors as $k=>$v) {
        $errMsg .= $v."<br />";
      }
    } else {
      if($db->saveUser($name, $email, $city, $country)) {
        $statusMsg = 'Success';
      } else {
        $statusMsg = 'Error in processing ...';
      }
    }
  }
 ?>
<h1>CRUD Operation</h1>
<div>
<div class="add-data">
  <span style="color: rgb(255, 0 ,0)";><?php echo $errMsg;?></span>
  <span class="statusMsg" style="color: rgb(0,255,0)"><?php echo $statusMsg?></span>
  <form method="post" action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>>
    <label>Name: </label>
    <input type="text" name="name" />
    <br />

    <label>Email: </label>
    <input type="email" name="email" />
    <br />

    <label>City: </label>
    <select name="city">
    </select>
    <br />

    <label>Country: </label>
    <input type="text" name="country" readonly />
    <br />

    <input type="submit">
  </form>
</div>

<div class="display"></div>

</div>
