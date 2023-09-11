<?php

function getAllFlights($option, $text = '', $id = '') {
    $Json = file_get_contents('./flights.json');
    $Array = json_decode($Json);

    $arr=[];
        if(!empty($option)) {
            if(!empty($text)) {
                foreach ($Array->flights as $key => $value) {
                    if(substr($Array->flights[$key]->name, 0 ,strlen($text)) == $text) {
                        array_push($arr, $Array->flights[$key]);
                    }
                }
            } else {
                array_push($arr, $Array->$option);
            }
        } else if(isset($id)) {
            foreach ($Array->flights as $key => $value) {
                if($Array->flights[$key]->flight_id == $id) {
                    array_push($arr, $Array->flights[$key]->available);
                }
            }
        }
        return $arr;
}

if (isset($_GET['option'])) {
    $searchString = $_GET['option'];
        $arr = getAllFlights($searchString,'','');
        echo json_encode($arr[0]);
} else if (isset($_GET['query']) && !isset($_GET['id'])) {
    $header = getallheaders();
    $key = null;

    if(array_key_exists('apiKey', $header)) {
    $key = $header['apiKey'];
    }

    if($key == "key") {

    $searchString = $_POST['text'];
    $arr = getAllFlights('all', $searchString,'');
    $array["status"] = 200;
    $array["data"] = $arr;
    echo json_encode($array);

    }
} else {
    if (isset($_GET['id'])) {
        $arr = getAllFlights('', '', $_GET['id']);
        $array["status"] = 200;
        $array["data"] = $arr;
        echo json_encode($array);
    } else {
        echo json_encode(array("status"=> 401));
    }
}
?>
