<html>
<head>
    <title>phpBasic</title>
</head>
<body>
<?php
$nameErr = $emailErr ="";
$DisplayForm = TRUE;
$checkToDisplay = TRUE;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
          $nameErr = "Name is required";
          $checkToDisplay = FALSE;
        } else {
          echo $_POST['name'];
        }
        if (empty($_POST["email"])) {
            $emailErr = "E-mail is required";
            $checkToDisplay = FALSE;
          } else {
            echo $_POST['email'];
          }

        if($checkToDisplay){
            $DisplayForm = FALSE;
        }
    }
if($DisplayForm){
?>
<form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
Name: <input type="text" name="name">
<span class="error">* <?php echo $nameErr;?></span>
<br><br>
E-mail: <input type="text" name="email">
<span class="error">* <?php echo $emailErr;?></span>
<br><br>
Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="upload">
<br><br>
<input type="reset" name="reset">
<input type="submit" name="submit">
</form>
<?php
}
$target_dir = "";
if(isset($_FILES['fileToUpload'])){
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
copy($_FILES["fileToUpload"]["name"],"".$_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
}
?>
<img src="<?php echo "".$_FILES["fileUpload"]["name"];?>">
</body>
</html>