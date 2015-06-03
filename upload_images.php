<?php
//Loop through each file

$uiid = $_POST['uiid'];

function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    return imagejpeg($image, $destination, $quality);
}


$uploaded_files;
//var_dump($_FILES['file']['name']);
for($i=0; $i<count($_FILES['file']['name']); $i++) {
        //Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'][$i];
        //var_dump($tmpFilePath);
        //Make sure we have a filepath
        if ($tmpFilePath != "") {
            //Setup our new file path
            $fileName = $uiid."_".$_FILES['file']['name'][$i];
            $fileName = preg_replace("/ /","_",$fileName);
            $newFilePath = "./uploaded_images/" . $fileName;

            //var_dump($newFilePath);

            //Upload the file into the temp dir
            if (compress($tmpFilePath, $newFilePath, 75)) {
                $uploaded_files .= $fileName . " ";
            }
        }
}

$data = (object)array(
    'uploaded_files' => $uploaded_files
);

$json = json_encode($data, JSON_UNESCAPED_UNICODE);

echo $json;