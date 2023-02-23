<?php
$uploaded = false;
$text = "";
if(!isset($_COOKIE["counter"])){
    setcookie("counter", 0, time() + (86400 * 30), "/");
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    try{
        // Check if image file is an actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if(!$check) {
                throw new Exception("File is not an image.");
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            throw new Exception("Sorry, file already exists.");
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            throw new Exception("Sorry, your file is too large.");
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $uploaded = true;
            // increment counter cookie
            setcookie("counter", $_COOKIE["counter"]+1, time() + (86400 * 30), "/");
        }
    } catch(Exception $e){
        $text = $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>
    <?php
    echo $text;
    if($uploaded){
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded. ";
    }else if ($_SERVER["REQUEST_METHOD"] == "POST" && $text == ""){
        echo "Sorry, there was an error uploading your file.";
    }
    echo  "You have uploaded" . $_COOKIE["counter"] . " files";
    ?>


    <?php include "footer.php"; ?>
</body>
</html>