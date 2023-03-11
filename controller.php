<?php
if(session_id() == ''){ session_start();}
include 'model.php';
// this function get the file read from the model file and
// filters into current or completed to present to the user on the viewer files.
function getFilterAssessments ($file , $filter)
{
    //it's calling the function from model that read the file and put the assessments into an array
    $assessments = getAssessments($file);
    $filteredAssessments = [];
    // a for loop to filter by the index5 which is where the words current and completed are allocated on the file.
    for($i = 0; $i < count($assessments); $i++) {
        if ($assessments[$i][5] == $filter) {
            $filteredAssessments[] = $assessments[$i];
        }
    }
    return $filteredAssessments;
}
// this function is to get the function from model and present to the view file once this two files can't
// have access to each other
function setAssessmentsStatus ($assessments, $status, $file){
    unset($_POST);
    setStatus($assessments, $status, $file);
}
// this function is to call the function from model to save the file when it is uploaded
function uploadFile($file){
    if($file['csv']['size'] < 1){
        echo 'Please enter a file';
        return;
    }
    saveFile($file);
}
// this function i to call the function from model and use the file uploaded and show to the user.
function getAllFiles(){

    $userFiles = getFileDb();
    $userArray = array_shift($userFiles);

   return array_filter(array_slice(getDictory(),2),function ($file) use($userArray) {
       return in_array($file,$userArray);
    } );
}

//get the function to save the new users from model to connect with the viewer
function saveNewUser($newUserName, $newUserEmail, $newUserPassword){
   return writeNewUser($newUserName, $newUserEmail, $newUserPassword);

}

// get the function from model to get user login info to validate on the viewer
function getUserLogin($userName, $userPassword){
    return getUser($userName, $userPassword);
}

