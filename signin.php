<?php require_once 'includes/config.php';?>

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
    <script src="assets/js/commonActions.js"></script>
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
                    <input type="text" name='username' placeholder='Username' autocomplete='off' required>
                    <input type="password" name='password' placeholder='Password' autocomplete='off' required>
                    <input type="submit" name='submitButton' value='SUBMIT'>
                </form>
            </div>
            <a href="signup.php" class='signInMessage'>Don't have an account? Sign up here.</a>
        </div>
    </div>

</body>
</html>