<?php
require_once 'includes/config.php';
require_once 'includes/classes/Account.php';
require_once 'includes/classes/Constants.php';
require_once 'includes/classes/FormSanitizer.php';

$account = new Account($con);

if (isset($_POST['submitButton'])) {
  $username  = FormSanitizer::sanitizeFormUsername($_POST['username']); // Username
  $passsword = FormSanitizer::sanitizeFormPassword($_POST['password']); // Password

  $wasSuccessfull = $account->login($username, $passsword);

  if ($wasSuccessfull) {
    // Successfully logged in and setup session variable for username
    $_SESSION['userLoggedIn'] = $username;
    // Redirect
    header('Location: index.php');
  }
}

function getInputValue($name) {
  if (isset($_POST[$name])) {
    echo $_POST[$name];
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Youtube - Sign In</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css">
     <script src="assets/js/jquery/jquery-3.5.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
</head>
<body>

    <div class='signInContainer'>
        <div class='column'>
            <div class="header">
                <img src="assets/images/icons/VideoTubeLogo.png" alt="Site Logo">
                <h3>Sign In</h3>
                <span>To continue to VideoTube</span>
            </div>
            <div class="loginForm">
                <form action='signin.php' method='POST'>

                    <?=$account->getError(Constants::$loginFailed);?>
                    <input type="text" name='username' placeholder='Username' autocomplete='off' required value='<?=getInputValue('username');?>'>

                    <input type="password" name='password' placeholder='Password' autocomplete='off' required>
                    <input type="submit" name='submitButton' value='SUBMIT'>
                </form>
            </div>
            <a href="signup.php" class='signInMessage'>Don't have an account? Sign up here.</a>
        </div>
    </div>

</body>
</html>