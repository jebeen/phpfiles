<?php
class Database {
    private $conn, $host, $username, $password, $dbname;
    public function __construct(string $dbname, string $username, string $password, string $host) {
    $this->host = $host;
    $this->dbname = $dbname;
    $this->username = $username;
    $this->password=$password;
    }

    public function getConnection() {
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
    }

    public function getAllMovies() {
        $stmt = $this->conn->prepare("SELECT mid,title,thumbnail,category,description,actor,director,name, email FROM movies join users on (movies.uid = users.eid) ORDER BY mid ASC");
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getMoviesReviews($id) {
        $stmt = $this->conn->prepare("SELECT id, uid, comment, posted_on, name, email from reviews join users on(reviews.uid = users.eid) where mid=".$id." and status=1 ORDER BY posted_on DESC");
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updateComment($data) {
        extract($data);
        $stmt= $this->conn->prepare("update reviews set comment='".$comment."', posted_on=curdate() where mid=$mid and uid=$uid and id=$id");
        $result = $stmt->execute();
        if($result) {
            return 1;
        }
        return 0;
    }

    public function removeComment($post_data) {
        extract($post_data);
        $stmt= $this->conn->prepare("update reviews set status=0 where id=$id");
        $result = $stmt->execute();
        if($result) {
            return 1;
        }
        return 0;
    }
}
?>
