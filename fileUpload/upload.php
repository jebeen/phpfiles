<html>
<head>
  <script src="../../bootstrap/js/jquery.min.js" type="text/javascript"></script>
</head>
<body>
<?php
$msg="";
if(!empty($_FILES)){
  $errors=array();
  $type=array("image/png", "application/pdf", "image/jpg", "image/jpeg");

  foreach($_FILES as $file){
    for($k=0;$k<count($file['name']);$k++) {
      // Validate File
      if(!empty($file['tmp_name'][$k])) {
        if(!getImageSize($file['tmp_name'][$k])) {
          $errors[$file['name'][$k]][] = "invalid image";
        }
      } else {
        $errors[$file['name'][$k]][] = "invalid image";
      }

      //Validate File Type
      if(!empty($file['type'][$k])) {
        if(!in_array($file['type'][$k], $type)) {
          $errors[$file['name'][$k]][] = "Invalid type";
        }
      } else {
        $errors[$file['name'][$k]][]="Invalid Type";
      }

      //Validate File Size
      if($file['size'][$k] > 40000 || $file['size'][$k] == 0) {
        $errors[$file['name'][$k]][] = "Max size";
      }

    }
  }

  if(count($errors)) {
    $msg="";
    foreach($errors as $key=>$error) {
      $msg.="<div>".$key." Errors ";
      foreach($error as $err){
        $msg.="<p>".$err."</p>";
      }
      $msg.="</div>";
    }
  } else {
    $targetDir='./uploads/';
    $uploaded=0;
    foreach($_FILES as $file) {
      for($k=0;$k<count($file['name']);$k++) {
        $targetFileName=$file['name'][$k]. "-" .Date('m-d-Y');
        $targetFile=$targetDir.$targetFileName;
        if(move_uploaded_file($file['tmp_name'][$k], $targetFile)) {
          ++$uploaded;
        } else {
          $msg="Error in Upload";
        }
      }
    }

    if($uploaded == count($_FILES['image']['name'])) {
      $msg="Successfully uploaded";
    }
  }
}

 ?>
 <span class="uploadErrors"><?= $msg ?></span>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" enctype="multipart/form-data">
   <input type="file" name="image[]" multiple="multiple" />
   <input type="submit">
 </form>
</body>
</html>
