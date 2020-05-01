<?php

require_once 'includes/classes/User.php';

class ButtonProvider {

  public static $signInFunction = 'notSignedIn()';

  public static function createLink($link) {
    return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
  }

  public static function createButton($text, $imgSrc, $action, $class) {
    $image  = ($imgSrc == null) ? '' : "<img src='$imgSrc' alt='$text'>";
    $action = ButtonProvider::createLink($action);
    return "
        <button class='$class' onclick='$action'>
            $image
            <span class='text'>$text</span>
        </button>
        ";
  }
}

?>