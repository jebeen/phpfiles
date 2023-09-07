
<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/". "$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

$movie= new movieController();

$db=new Database('dbname','username','password','hostname');

$db->getConnection();

$result = $db->getAllMovies();

$categoryList = $movie -> getCategories();

function display($movies, $reviews = [], $mid='') {
    $products=new products();
    $products->displayMovies($movies);
    if(count($reviews)) {
        $products->displayReviews($reviews, $mid);
    }
}

function updateResult($v, $list) {
        if($m = array_search($v, $list)) {
            return $m;
        }
}

if($_SERVER['REQUEST_URI'] == '/movierating/') {
        foreach ($result as $key => $value) {
        foreach ($result[$key] as $k => $v) {
            if($k == "category") {
                    $result[$key][$k] = updateResult($v, $categoryList);
            }
        }
    }
    if(count($result)) {
       display($result);
    } else {
        echo "No records";
    }
}
else if(isset($_GET['id'])) {
        $movies = array_filter($result, function($movie) {
            $id = $_GET['id'];
            if($movie['mid'] == $id) {
                return $movie;
            }

        });
        $reviews = $db -> getMoviesReviews($_GET['id']);

        foreach ($movies as $key => $value) {
            foreach ($movies[$key] as $k => $v) {
                if($k == "category") {
                        $movies[$key][$k] = updateResult($v, $categoryList);
                }
            }
        }
        display($movies, $reviews, $_GET['id']);
}

if(isset($_POST) && isset($_POST['edit']) && $_POST['edit'] == 1) {
    if($db->updateComment($_POST)){
        header('refresh');
    }
    else {
        http_response_code(500);
        echo json_encode(["message"=>"Problem in updating"]);
        return;
    }
}

if(isset($_POST) && isset($_POST['delete']) && $_POST['delete'] == 1) {
    if($db->removeComment($_POST)){
        header('refresh');
    }
    else {
        http_response_code(500);
        echo json_encode(["message"=>"Problem in remove comment"]);
        return;
    }
}
?>
