<?php
$test_json = '{
  "database.host": "127.0.0.1",
  "database.user": "test",
  "database.password": "qwerty",
  "rest.photo.host": "127.0.0.2",
  "rest.users.host": "127.0.0.3",
  "rest.users.password": "p@ssw0rd",
  "baseUrl": "test.com",
  "limit": 10
}';

//var_dump(json_decode($test_json));
$test_array = json_decode($test_json, true);

echo $test_array;

$array_final = array();

while (list($key, $value) = each($test_array)) {
    //var_dump($key.":".$value);


    $key_array = explode(".", $key);

    //var_dump($key_array);

    for ($i = count($key_array) - 1; $i > 0; $i--) {

        $temp_array = array();

        if($i == count($key_array) - 1) {
            array_push($temp_array, array(
                $key_array[$i] => $value
            ));
        }




    }

}

echo json_encode($array_final);