<?php
require_once 'includes/header.php';
require_once 'includes/classes/ProfileGenerator.php';

if (!isset($_GET['username']) || $_GET['username'] == '') {
  echo "<h1 class='display-4 my-3'>Channel not found</h1>";
  exit();
} else {
  $profileusername = $_GET['username'];
}

$profileGenerator = new ProfileGenerator($con, $userLoggedInObj, $profileusername);
echo $profileGenerator->create();
?>

<?php require_once 'includes/footer.php';?>


