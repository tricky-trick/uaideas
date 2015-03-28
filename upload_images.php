<?php
//Loop through each file
$uploaded_files;
//var_dump($_FILES['file']['name']);
for($i=0; $i<count($_FILES['file']['name']); $i++) {
        //Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'][$i];
        //var_dump($tmpFilePath);
        //Make sure we have a filepath
        if ($tmpFilePath != "") {
            //Setup our new file path
            $fileName = "idea_".(round(time()/60/60/24 - 100))."_".$_FILES['file']['name'][$i];
            $newFilePath = "./uploaded_images/" . $fileName;

            //var_dump($newFilePath);

            //Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $uploaded_files .= $fileName . " ";
            }
        }
}

$data = (object)array(
    'uploaded_files' => $uploaded_files
);

$json = json_encode($data, JSON_UNESCAPED_UNICODE);

echo $json;