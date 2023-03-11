<?php
if(session_id() == ''){ session_start();}

if(!isset($_SESSION['userId'])){

    header("Location:index.php");
}
include 'controller.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Assignment 3</title>
</head>
<body>
<!-- This is the upload nav bar-->

<div class="menubar">
    <!-- to go to the chosen file when the user change through the option, I added the Get for the file chosen on upload-->
    <a class="active" href='viewCurrent.php?f=<?php echo $_GET['f'] ? $_GET['f'] : ''; ?>'>Current</a>
    <a class="active" href='viewCompleted.php?f=<?php echo $_GET['f'] ? $_GET['f'] : ''; ?>'>Completed</a>
    <a class="active" href='viewUpload.php?f=<?php echo $_GET['f'] ? $_GET['f'] : ''; ?>'>Uploaded</a>
    <a class="active" href="index.php?f=logout">Logout</a>
    <hr>
</div>

<h1>Upload Assessments File</h1>

<form action="viewUpload.php" method="post" enctype="multipart/form-data">
    <labe>Upload a file :</labe>
    <input type="file" name="csv" value="" />
    <br>
    <input type="submit" name="submit" value="Upload" />
    <p>CSV file must be in the following format - id,course,_name,assessment_name,date,status[Completed/Current] </p>
    <hr>
</form>

<?php

// this loop is for when the user upload the file, we used the function uploadFiles to save it.
if(isset($_POST['submit'])){
    uploadFile($_FILES);
}
//this function is to display the files that it was uploaded
function displayFile(){
    echo "<h2>Files Previously Uploaded (Now in data Folder)</h2>";
    echo "<ul>";
    // foreach loop to go through all the save files, put in list and display it
    foreach (getAllFiles() as $file){
        echo "<li><a href='viewCurrent.php?f=$file'> $file</a></li>";
    }
    echo "</ul>";
}
displayFile();
?>
<hr>
<h2>Footer</h2>
<?php
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo show_source("viewUpload.php")
?>
