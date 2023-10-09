<?php
class DataBase {

  public $conn;

  public function __construct($dbname, $hostname, $username, $password) {
    try {
      $this->conn = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", $username, $password);
    } catch(PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function saveUser($name, $email, $city, $country) {
    $query=$this->conn->prepare("insert into users (name, email, city, country) values (".'"'.$name.'"'.",".'"'.$email.'"'.",".'"'.$city.'"'.",".'"'.$country.'"'.")");
    $result=$query->execute();
    if($result) {
      return 1;
    }
    return 0;
  }

  public function getAllUsers() {
    $query=$this->conn->prepare("select eid, name, email, city, country from users");
    $query->execute();
    $result=$query->fetchAll(PDO::FETCH_ASSOC);
    if($result) {
      return $result;
    }
    return [];
  }

  public function updateUser($data) {
    $query=$this->conn->prepare("update users set name=".'"'.$data[1].'"'.",email=".'"'.$data[2].'"'.",city=".'"'.$data[3].'"'.",country=".'"'.$data[4].'"'." where eid=".$data[0]);
    $result=$query->execute();
    if($result) {
      return 1;
    }
    return 0;
  }

  public function deleteUser($id) {
    $query=$this->conn->prepare("delete from users where eid=".$id);
    $result=$query->execute();
    if($result) {
      return 1;
    }
    return 0;
  }

}
?>
