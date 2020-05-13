<?php

class SettingsFormProvider {

  public function createUserDetailsForm($firstName, $lastName, $email) {
    $firstNameInput = $this->createFirstNameInput($firstName);
    $lastNameInput  = $this->createLastNameInput($lastName);
    $emailInput     = $this->createEmailInput($email);
    $saveButton     = $this->createSaveUserDetailsButton();

    return "
    <form action='settings.php' method='POST'>
        <span class='title'>User Details</span>
        $firstNameInput
        $lastNameInput
        $emailInput
        $saveButton
    </form>
    ";
  }

  public function createPasswordForm() {
    $oldPasswordInput    = $this->createPasswordInput('oldPassword', 'Old Password');
    $newPasswordOneInput = $this->createPasswordInput('newPasswordOne', 'New Password');
    $newPasswordTwoInput = $this->createPasswordInput('newPasswordTwo', 'Confirm New Password');
    $saveButton          = $this->createSavePasswordButton();

    return "
    <form action='settings.php' method='POST'>
        <span class='title'>Update Password</span>
        $oldPasswordInput
        $newPasswordOneInput
        $newPasswordTwoInput
        $saveButton
    </form>
    ";
  }

  private function createFirstNameInput($value = null) {
    $value = ($value == null) ? $value = '' : $value;
    return "
    <div class='form-group'>
        <input type='text' class='form-control' name='firstName' placeholder='First Name' value='$value' required>
    </div>
    ";
  }

  private function createLastNameInput($value = null) {
    $value = ($value == null) ? $value = '' : $value;
    return "
    <div class='form-group'>
        <input type='text' class='form-control' name='lastName' placeholder='Last Name' value='$value' required>
    </div>
    ";
  }

  private function createEmailInput($value = null) {
    $value = ($value == null) ? $value = '' : $value;
    return "
    <div class='form-group'>
        <input type='email' class='form-control' name='email' placeholder='Email' value='$value' required>
    </div>
    ";
  }

  private function createPasswordInput($name, $placeholder) {
    return "
    <div class='form-group'>
        <input type='password' class='form-control' name='$name' placeholder='$placeholder' required>
    </div>
    ";
  }

  private function createSaveUserDetailsButton() {
    return "<button type='submit' name='saveDetailsButton' class='btn btn-primary'>Save</button>";
  }

  private function createSavePasswordButton() {
    return "<button type='submit' name='savePasswordButton' class='btn btn-primary'>Save</button>";
  }
}

?>