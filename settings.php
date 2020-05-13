<?php
require_once 'includes/header.php';
require_once 'includes/classes/Account.php';
require_once 'includes/classes/Constants.php';
require_once 'includes/classes/FormSanitizer.php';
require_once 'includes/classes/SettingsFormProvider.php';

if (!User::isLoggedIn()) {
  header('Location: signin.php');
}

$detailsMessage       = '';
$passwordMessage      = '';
$settingsFormProvider = new SettingsFormProvider();

$firstName = isset($_POST['firstName']) ? $_POST['firstName'] : $userLoggedInObj->getFirstName();
$lastName  = isset($_POST['lastName']) ? $_POST['lastName'] : $userLoggedInObj->getLastName();
$email     = isset($_POST['email']) ? $_POST['email'] : $userLoggedInObj->getEmail();

if (isset($_POST['saveDetailsButton'])) {
  $account = new Account($con);

  $firstName = FormSanitizer::sanitizeFormString($firstName);
  $lastName  = FormSanitizer::sanitizeFormString($lastName);
  $email     = FormSanitizer::sanitizeFormEmail($email);
  $username  = $userLoggedInObj->getUsername();

  if ($account->updateDetails($firstName, $lastName, $email, $username)) {
    $detailsMessage = "
        <div class='alert alert-success'>
           <strong>SUCCESS!</strong> Details updated successfully.
        </div>
    ";
  } else {
    $errorMsg       = $account->getFirstError();
    $errorMsg       = ($errorMsg == '') ? 'Something went wrong' : $errorMsg;
    $detailsMessage = "
        <div class='alert alert-danger'>
           <strong>ERROR!</strong> $errorMsg.
        </div>
    ";
  }
}

if (isset($_POST['savePasswordButton'])) {
  $account = new Account($con);

  $oldPassword    = FormSanitizer::sanitizeFormPassword($_POST['oldPassword']);
  $newPasswordOne = FormSanitizer::sanitizeFormString($_POST['newPasswordOne']);
  $newPasswordTwo = FormSanitizer::sanitizeFormEmail($_POST['newPasswordTwo']);
  $username       = $userLoggedInObj->getUsername();

  if ($account->updatePassword($oldPassword, $newPasswordOne, $newPasswordTwo, $username)) {
    $passwordMessage = "
        <div class='alert alert-success'>
           <strong>SUCCESS!</strong> Password updated successfully.
        </div>
    ";
  } else {
    $errorMsg        = $account->getFirstError();
    $errorMsg        = ($errorMsg == '') ? 'Something went wrong' : $errorMsg;
    $passwordMessage = "
        <div class='alert alert-danger'>
           <strong>ERROR!</strong> $errorMsg.
        </div>
    ";
  }
}

?>

<div class="settingsContainer column">

    <div class="formSection">
        <div class='message'>
            <?php echo $detailsMessage; ?>
        </div>
        <?php echo $settingsFormProvider->createUserDetailsForm($firstName, $lastName, $email); ?>
    </div>
    <div class="formSection">
        <div class='message'>
            <?php echo $passwordMessage; ?>
        </div>
        <?php echo $settingsFormProvider->createPasswordForm(); ?>
    </div>
</div>


<?php require_once 'includes/footer.php';?>
