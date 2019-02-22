<html>
<head>
    <title>phpBasic</title>
</head>
<body>
<?php
$DisplayForm = TRUE;
$DisplayPic = FALSE;
$DisplayCSV = FALSE;
$target_dir = "";
$nameErr = $emailErr = $picErr = $csvErr = "";
$checkToDisplay = TRUE;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
          $nameErr = "Name is required";
          $checkToDisplay = FALSE;
        }
        if (empty($_POST["email"])) {
            $emailErr = "E-mail is required";
            $checkToDisplay = FALSE;
        }
        if($checkToDisplay){
            $DisplayForm = FALSE;
        }
    }
if(isset($_FILES['fileToUpload'])){
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

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
        $DisplayPic = TRUE;
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
}
if(isset($_FILES['fileCSVToUpload'])){
    $target_CSVfile = $target_dir . basename($_FILES["fileCSVToUpload"]["name"]);
    $uploadCSVOk = 1;
    $csvFileType = strtolower(pathinfo($target_CSVfile,PATHINFO_EXTENSION));
    
    // Allow certain file formats
    if($csvFileType != "csv") {
        echo "Sorry, only CSV file are allowed.";
        $uploadCSVOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadCSVOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileCSVToUpload"]["tmp_name"], $target_CSVfile)) {
            $csvFile = fopen($target_CSVfile,"r");
            $word = fread($csvFile,filesize($target_CSVfile));
            fclose($csvFile);
            $array = preg_split("/[\s,]+/",$word);
            $allBMI = 0;
            $countBMI = 0;
            for($x = 2;$x < count($array);$x=$x+2){
                $tempX = $x;
                $height = $array[$tempX];
                $weight = $array[$tempX+1];
                $bmi = intval($weight)/(intval($height)/100)**2;
                $countBMI++;
                $allBMI = $allBMI+$bmi;
            }
            $avg = $allBMI/$countBMI;
            $DisplayCSV = TRUE;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if($DisplayPic && !$DisplayForm && $DisplayCSV){
?>
<img src="<?php echo $_FILES["fileToUpload"]["name"];?>">
<p>Name : <?php echo $_POST["name"] ?></p>
<p>E-mail : <?php echo $_POST["email"] ?></p>
<p>Average BMI : <?php echo $avg ?></p>
<?php
}

if($DisplayForm || !$DisplayPic || !$DisplayCSV){
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
    <p class="error"><?php echo $picErr;?></p>
Select CSV to upload:
    <input type="file" name="fileCSVToUpload" id="fileToUpload">
    <p class="error"><?php echo $csvErr;?></p>
<input type="reset" name="reset">
<input type="submit" name="submit">
</form>
<?php
}


?>

</body>
</html>