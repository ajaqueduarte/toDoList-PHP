<?php
ob_start();
if(session_id() == ''){ session_start();}
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

<h1>To do List</h1>

<h3>Here you can have a better control of your tasks</h3>
<hr>

<!--this is the login form -->
<h2>Login</h2>

<form action="index.php" method="post">

    <div class="container">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required>
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" required>

        <button type="submit" name="submit">Login</button>
    </div>

</form>

<br>
<br>
<br>
<hr>
<!--this is the create new user form -->
<h2>Create an Account</h2>
<form action="index.php" method="post">

    <div class="container">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="newUsername" required>
        <br>
        <label for="psw"><b>Email</b></label>
        <input type="email" placeholder="Enter valid email" name="newEmail" required>
        <br>
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="newPsw" required>
        <br>
        <label for="psw"><b>Confirm Password</b></label>
        <input type="password" placeholder="Enter Password" name="confirmPsw" required>
        <br>
        <button type="submit" name="newAccount">Create Account</button>
    </div>
    <hr>

</form>

<?php
// creating new user and validating the data
if(isset($_POST['newAccount']) && validatationNewUser()){
    if(saveNewUser($_POST['newUsername'],$_POST['newEmail'],$_POST['newPsw'])){
        setUserLogin($_POST['newUsername'],$_POST['newPsw']);
    }
}
// function to check on database if the user exist
function setUserLogin($username,$password){
    $user = getUserLogin($username,$password);
    if($user){
        $_SESSION['userId']= $user['id'];
        header ("Location:viewUpload.php");
    }
}

// loging the user into webpage and validating if user exist
if(isset($_POST['submit']) && validationLogin()){
    setUserLogin($_POST['username'],$_POST['psw']);
}
// log out - cleaning the values so if the person logout it can't come back unless log in again
if($_GET['f']== 'logout' || !isset($_SESSION['userId'])){
    unsetValues();
}


// logout and clean cash
function unsetValues(){
    unset($_POST);
    unset($_SESSION);
    unset($_GET);
    session_destroy();
}

// function to validate new user data when create a new account
function validatationNewUser(){
    if(!isset($_POST['newUsername']) || strlen($_POST['newUsername']) <4
        || strlen($_POST['newUsername'])> 8 || !ctype_alpha($_POST['newUsername']) || empty($_POST['newUsername'])){
        echo "User name must be between 4 and 8 letters";
        unsetValues();
        return false;
    }
    if(!isset($_POST['newEmail']) || !filter_var($_POST['newEmail'], FILTER_VALIDATE_EMAIL)
        || empty($_POST['newEmail'])){
        echo "Invalid email format";
        unsetValues();
        return false;
    }
    if(!isset($_POST['newPsw']) || strlen($_POST['newPsw'])< 8){
        echo "Your password must have more than 8 characters";
        unsetValues();
        return false;
    }
    if($_POST['newPsw'] !== $_POST['confirmPsw']) {
        echo "Password and Confirm Password fields must be the same";
        unsetValues();
        return false;
    }

    return true;
}
// function to validate login data, checking if the user is registered
function validationLogin(){
    if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['psw']) && !empty($_POST['psw'])){

        return true;
    }
    echo "User or password invalid";
    unsetValues();
    return false;
}
echo '<br>';
echo show_source("index.php")
?>

</body>
</html>