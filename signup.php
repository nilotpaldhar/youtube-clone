<?php require_once 'includes/config.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Youtube - Sign Up</title>
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
                <h3>Sign Up</h3>
                <span>To continue to VideoTube</span>
            </div>
            <div class="loginForm">
                <form action='signup.php' method='POST'>
                    <input type="text" name='firstName' placeholder='First Name' autocomplete='off' required>
                    <input type="text" name='lastName' placeholder='Last Name' autocomplete='off' required>
                    <input type="text" name='username' placeholder='Username' autocomplete='off' required>

                    <input type="email" name='email' placeholder='Email' autocomplete='off' required>
                    <input type="email" name='email2' placeholder='Confirm Email' autocomplete='off' required>

                    <input type="password" name='password' placeholder='Password' autocomplete='off' required>
                    <input type="password" name='password2' placeholder='Confirm Password' autocomplete='off' required>

                    <input type="submit" name='submitButton' value='SUBMIT'>
                </form>
            </div>
            <a href="signin.php" class='signInMessage'>Already have an account? Sign in here.</a>
        </div>
    </div>

</body>
</html>