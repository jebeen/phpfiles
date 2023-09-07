<?php
class movieController {
    public function __construct() {

    }

    public function getCategories() {
        $categoryList=array(
            "scientific" => 1,
            "fiction" => 2,
            "commercial" => 3
        );
        return $categoryList;
    }
}

?>
