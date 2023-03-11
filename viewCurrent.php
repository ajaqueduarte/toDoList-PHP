<!-- Student ID: 101400994-->
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

<?php
// I am using this loop, to get the file that is chosen from Upload
if (!isset($_GET['f'])){
    header("Location: viewUpload.php" );
}
?>
<!-- This is the Current nav bar-->
<div class="menubar">
    <!-- to go to the chosen file when the user change through the option, I added the Get for the file chosen on upload-->
    <a class="active" href='viewCurrent.php?f=<?php echo $_GET['f']; ?>'>Current</a>
    <a class="active" href='viewCompleted.php?f=<?php echo $_GET['f']; ?>'>Completed</a>
    <a class="active" href='viewUpload.php?f=<?php echo $_GET['f']; ?>'>Uploaded</a>
    <a class="active" href="index.php?f=logout">Logout</a>
    <hr>
</div>

<h1>List of Current Assessments</h1>

<?php
include 'controller.php';

// this function will display the options with the checkbox
function display(){
    //using the function from controller to filter only the assignments that I need. In this case current
    $currentAssessments = getFilterAssessments($_GET['f'], 'Current');
    if($currentAssessments){
        echo "<form action='viewCurrent.php?f=".$_GET['f']."'method='post'>";
        // foreach to present the assignments with a checkbox for the user mark
        foreach ($currentAssessments as $currentAssessment) {
            //I had to use an arraypop function to not show the word current/completed as the assignment requested
            array_pop($currentAssessment);
            echo implode(" ", $currentAssessment ). "<input type='checkbox' name='$currentAssessment[0]'/>" . "<br>";
        }
        // the submit button is also here, so if there is no assignment we do not need to display the button.
        echo "<input type='submit' name='submit' value='update'/>";
        echo "</form>";
    }
    // if there is no more assessment left display this
    else{
        echo "There is no current assessment";
    }
}
//this loop is for the submit button, when it presses it will get the arrays/assessments that is marked and it will
//transfer to the completed tab and vice versa.
if(isset($_POST['submit'])){
    $assessments = array_keys($_POST);
    array_pop($assessments);
    // I am calling the function from controller to change only status and write in the file
    setAssessmentsStatus($assessments, 'Completed', $_GET['f']);

}

// Im calling the display function again, so it refresh the page with the assessments left or the message with no assessments
display();
?>
<hr>
<h2>Footer</h2>
<?php
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo show_source("viewCurrent.php")
?>




