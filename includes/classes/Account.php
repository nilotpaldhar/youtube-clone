<?php

class Account {
  private $con;
  private $errorArray = [];

  public function __construct($con) {
    $this->con = $con;
  }

  public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateUsername($un);
    $this->validateEmail($em, $em2);
    $this->validatePassword($pw, $pw2);

    // Check if there is no error
    if (empty($this->errorArray)) {
      return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
    } else {
      return false;
    }
  }

  public function login($un, $pw) {
    // Hashed password
    $pw_hashed = hash('sha512', $pw);

    $query = $this->con->prepare('SELECT * FROM users WHERE username = :un AND password = :pw');
    $query->bindParam(':un', $un);
    $query->bindParam(':pw', $pw_hashed);
    $query->execute();

    if ($query->rowCount() == 1) {
      return true;
    } else {
      array_push($this->errorArray, Constants::$loginFailed);
      return false;
    }
  }

  public function updateDetails($fn, $ln, $em, $un) {
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateNewEmail($em, $un);

    if (empty($this->errorArray)) {
      $query = $this->con->prepare('UPDATE users SET firstName = :fn, lastName = :ln, email = :em
                                    WHERE username = :un');
      $query->bindParam(':fn', $fn);
      $query->bindParam(':ln', $ln);
      $query->bindParam(':em', $em);
      $query->bindParam(':un', $un);
      return $query->execute();
    } else {
      return false;
    }
  }

  public function updatePassword($oldPw, $pw, $pw2, $un) {
    $this->validateOldPassword($oldPw, $un);
    $this->validatePassword($pw, $pw2);
    $pw_hashed = hash('sha512', $pw);

    if (empty($this->errorArray)) {
      $query = $this->con->prepare('UPDATE users SET password = :pw WHERE username = :un');
      $query->bindParam(':pw', $pw_hashed);
      $query->bindParam(':un', $un);
      return $query->execute();
    } else {
      return false;
    }
  }

  public function insertUserDetails($fn, $ln, $un, $em, $pw) {
    // Hashed password
    $pw_hashed = hash('sha512', $pw);
    // Define a default profile pic
    $profilePic = 'assets/images/profilePictures/default.png';

    // Inserting user details
    $query = $this->con->prepare('INSERT INTO users(firstName, lastName, username, email, password, profilePic) VALUES(:fn, :ln, :un, :em, :pw, :profilePic)');
    $query->bindParam(':fn', $fn);
    $query->bindParam(':ln', $ln);
    $query->bindParam(':un', $un);
    $query->bindParam(':em', $em);
    $query->bindParam(':pw', $pw_hashed);
    $query->bindParam(':profilePic', $profilePic);

    return $query->execute();
  }

  private function validateFirstName($fn) {
    if (strlen($fn) > 25 || strlen($fn) < 2) {
      array_push($this->errorArray, Constants::$firstNameCharacters);
    }
  }

  private function validateLastName($ln) {
    if (strlen($ln) > 25 || strlen($ln) < 2) {
      array_push($this->errorArray, Constants::$lastNameCharacters);
    }
  }

  private function validateUsername($un) {
    if (strlen($un) > 25 || strlen($un) < 5) {
      array_push($this->errorArray, Constants::$usernameCharacters);
      return;
    }

    $query = $this->con->prepare('SELECT username from users WHERE username = :un');
    $query->bindParam(':un', $un);
    $query->execute();

    if ($query->rowCount() > 0) {
      array_push($this->errorArray, Constants::$usernameTaken);
    }
  }

  private function validateEmail($em, $em2) {
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
      array_push($this->errorArray, Constants::$emailNotValid);
      return;
    }

    if ($em != $em2) {
      array_push($this->errorArray, Constants::$emailsDoNotMatch);
      return;
    }

    $query = $this->con->prepare('SELECT email from users WHERE email = :em');
    $query->bindParam(':em', $em);
    $query->execute();

    if ($query->rowCount() > 0) {
      array_push($this->errorArray, Constants::$emailTaken);
    }
  }

  private function validateNewEmail($em, $un) {
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
      array_push($this->errorArray, Constants::$emailNotValid);
      return;
    }

    $query = $this->con->prepare('SELECT email from users WHERE email = :em AND username != :un');
    $query->bindParam(':em', $em);
    $query->bindParam(':un', $un);
    $query->execute();

    if ($query->rowCount() > 0) {
      array_push($this->errorArray, Constants::$emailTaken);
    }
  }

  private function validatePassword($pw, $pw2) {
    if ($pw != $pw2) {
      array_push($this->errorArray, Constants::$passwordsDoNotMatch);
      return;
    }

    if (preg_match('/[^A-Za-z0-9]/', $pw)) {
      array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
      return;
    }
    if (strlen($pw) <= 8) {
      array_push($this->errorArray, Constants::$passwordCharacters);
      return;
    }

  }

  private function validateOldPassword($oldPw, $un) {
    // Hashed password
    $pw_hashed = hash('sha512', $oldPw);

    $query = $this->con->prepare('SELECT * FROM users WHERE username = :un AND password = :pw');
    $query->bindParam(':un', $un);
    $query->bindParam(':pw', $pw_hashed);
    $query->execute();

    if ($query->rowCount() == 0) {
      array_push($this->errorArray, Constants::$passwordIncorrect);
    }
  }

  public function getError($error) {
    if (in_array($error, $this->errorArray)) {
      return "<small class='errorMessage'>$error</small>";
    }
  }

  public function getFirstError() {
    if (!empty($this->errorArray)) {
      return $this->errorArray[0];
    } else {
      return '';
    }
  }

}

?>