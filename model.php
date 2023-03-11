<?php
if(session_id() == ''){ session_start();}
// function to read the file and put the lines into arrays.
function getAssessments($file,$toValidate = false){

    $readingFile = fopen(dirname(__FILE__)."/files/".$file, 'r');
    // using while loop to put each item in an index of the second array
    while (!feof($readingFile) ) {
        $assessment = fgetcsv($readingFile,0,',');
        // using the function fgetcsv which is the type of file accepted on this assignment and using comma to define the separator.
        // this if it is to get just the elements with no booleans.
        if(!is_bool($assessment)){
            $assessments[] = $assessment;
        }
    }
    fclose($readingFile);
    // validate file
    if($toValidate){
       if(!validateFile($assessments)){
           return false;
       }

    }
    return $assessments;
}


// function to change the status of the assessments when the user select it and update it.
function setStatus($assessments, $status, $file){
    //calling the function that reads the file
    $fileAssessments = getAssessments($file);
    //foreach loop that gets only the status, if is current or completed
    foreach ($assessments as $assessment){
        $fileAssessments[$assessment-1][5] = $status;
    }
    // here is where it's going to change the status of the file
    //writing the new status only on the index of the array that has the status.
    $writingFile = fopen(dirname(__FILE__)."/files/".$file, 'w');
    foreach ($fileAssessments as $fileAssignment){
        fputcsv($writingFile, $fileAssignment);

    }
}

// function to save the uploaded files
function saveFile($file){
    $filename = $_SESSION['userId'].'-'.$file['csv']['name'];
    $uploadDir = dirname(__FILE__)."/files/".basename($filename);
    move_uploaded_file($file['csv']['tmp_name'],$uploadDir);

    if(!getAssessments($filename,true)){
        echo 'File is not in a valid format';
        unlink($uploadDir);
        return;
    }
    saveFileDb($filename);


}
// function to get all the files uploaded and saved.
function getDictory(){
    return scandir(dirname(__FILE__)."/files/");

}
// getting file on database
function getFileDb(){
    $db = connectDatabase();
    $query = "SELECT userfiles.filename FROM userfiles JOIN login ON userfiles.userid = login.id WHERE login.id = :id";
    $query = $db->prepare($query);
    $query->bindValue(":id",$_SESSION['userId']);
    $query->execute();


    return $query->fetchAll(PDO::FETCH_NUM);

}

//Connection with database
function connectDatabase(){
    $db_info = 'mysql:host=localhost;dbname=f2400994_assignment3';
    $username = 'f2400994_jaque';
    $password = 'jaque1992';

    try{
        $db_con = new PDO($db_info,$username,$password);
    }catch (PDOException $e){
        $error_message = $e -> getMessage();
        echo "PDO database not connected. Error: ".$error_message."<br>";
        exit();
    }
    return $db_con;
}

// write new user to database
function writeNewUser($newUserName, $newUserEmail, $newUserPassword){
    $db = connectDatabase();
    $query = "INSERT INTO login (username, useremail, password) VALUES (:newUserName, :newUserEmail, :newUserPassword)";

    $query = $db->prepare($query);
    $query->bindValue(":newUserName",$newUserName);
    $query->bindValue(":newUserEmail",$newUserEmail);
    $query->bindValue(":newUserPassword",$newUserPassword);

    return $query->execute();
}

//get username and password from database to validate the user
function getUser($userName,$userPassword){
    $db = connectDatabase();
    $query = "SELECT * FROM login WHERE username = :name and password = :password ";
    $query = $db->prepare($query);
    $query->bindValue(":name",$userName);
    $query->bindValue(":password",$userPassword);

    $query->execute();
    return $query->fetch();

}

//save file into using the user id, so other users doesn't have access
function saveFileDb($fileName){
    $db = connectDatabase();
    $query = "INSERT INTO userfiles (userId, fileName) VALUES (:userId, :fileName)";

    $query = $db->prepare($query);
    $query->bindValue(":userId",$_SESSION['userId']);
    $query->bindValue(":fileName",$fileName);

    return $query->execute();

}
// validating file
function validateFile($assessments){

    foreach ($assessments as $assessment){
        if(sizeof($assessment) != 6){
            return false;
        }
        if(!is_numeric($assessment[0])){
            return false;
        }
        if (strlen($assessment[1]) != 8){
            return false;
        }
        if(is_numeric($assessment[2])){
            return false;
        }
        $allowedStatus = ['Current','Completed'];
        if(!in_array($assessment[5],$allowedStatus)){
            return false;
        }
        foreach ($assessment as $cell){
            if(preg_match('/[?<>*]/', $cell)){
                return false;
            }
        }
    }
    return true;
}