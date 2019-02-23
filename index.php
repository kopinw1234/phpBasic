<html>
<head>
    <title>phpBasic</title>
    <link rel="stylesheet" href="mystyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css">
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
    $picErr = "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $picErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $picErr = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $DisplayPic = TRUE;
    }
}
}
if(isset($_FILES['fileCSVToUpload'])){
    $target_CSVfile = $target_dir . basename($_FILES["fileCSVToUpload"]["name"]);
    $uploadCSVOk = 1;
    $csvFileType = strtolower(pathinfo($target_CSVfile,PATHINFO_EXTENSION));
    
    // Allow certain file formats
    if($csvFileType != "csv") {
        $csvErr = "Sorry, only CSV file are allowed.";
        $uploadCSVOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadCSVOk == 0) {
        $csvErr = "Sorry, your file was not uploaded.";
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
        }
    }
}

if($DisplayPic && !$DisplayForm && $DisplayCSV){
?>
<div id="allgrid">
    <div id="header">
        <h1>Your BMI</h1>
    </div>
    <div id="img">
<img src="<?php echo $_FILES["fileToUpload"]["name"];?>">
</div>
<div id="paragraph">
<br>
<p>Name : <?php echo $_POST["name"] ?></p>
<p>E-mail : <?php echo $_POST["email"] ?></p>
<p>Average BMI : <?php echo $avg ?></p>
<br>
</div>
</div>
<?php
}

if($DisplayForm || !$DisplayPic || !$DisplayCSV){
?>
<section class="section">
<div class="level">
<div class="level-item has-text-centered">
<form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<div class="field">
<div class="level-left">
<label class="label">Name</label>
</div>
  <div class="control">
    <input class="input is-success" type="text" placeholder="Text input" name="name">
    <p class="help is-success"><?php echo $nameErr;?></p>
</div>
<div class="level-left">
<label class="label">E-mail</label>
</div>
  <div class="control">
    <input class="input is-success" type="text" placeholder="Text input" name="email">
    <p class="help is-success"><?php echo $emailErr;?></p>
</div>
</div>
<div class="level-left">
<div class="field">
  <div class="file is-info has-name">
    <label class="file-label">
      <input class="file-input" type="file" name="fileToUpload" id="file">
      <span class="file-cta">
        <span class="file-label">
          Image file…
        </span>
      </span>
      <span class="file-name" id="filename">
        No file chosen
      </span>
    </label>
  </div>
</div>
</div>
<div class="level-left">
<span class="error"><?php echo $picErr;?></span>
</div>
<br>
<div class="level-left">
<div class="field">
  <div class="file is-info has-name">
    <label class="file-label">
      <input class="file-input" type="file" name="fileCSVToUpload" id="csvFile">
      <span class="file-cta">
        <span class="file-label">
          CSV file…
        </span>
      </span>
      <span class="file-name" id="csvfilename">
        No file chosen
      </span>
    </label>
  </div>
</div>
</div>
<div class="level-left">
<span class="error"><?php echo $csvErr;?></span>
</div>    
<br>
<div class="level-left">
<input type="reset" name="reset" class="button is-light">
<input type="submit" name="submit" class="button is-success">
</div>
</form>
</div>
</div>
</section>
<?php
}


?>
<script src="main.js"></script>
</body>
</html>