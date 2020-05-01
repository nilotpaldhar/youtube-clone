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

  public static function createHyperlinkButton($text, $imgSrc, $href, $class) {
    $image = ($imgSrc == null) ? '' : "<img src='$imgSrc' alt='$text'>";
    return "
        <a href='$href'>
          <button class='$class'>
              $image
              <span class='text'>$text</span>
          </button>
        </a>
        ";
  }

  public static function createUserProfileButton($con, $username) {
    $userObj    = new User($con, $username);
    $profilePic = $userObj->getProfilePic();
    $link       = "profile.php?username=$username";

    return "
      <a href='$link'>
        <img src='$profilePic' alt='$username' class='profilePicture'>
      </a>
    ";
  }

  public static function createEditVideoButton($videoId) {
    $href   = "editVideo.php?videoId=$videoId";
    $button = ButtonProvider::createHyperlinkButton('EDIT VIDEO', null, $href, 'edit button');

    return "
      <div class='editVideoButonContainer'>
        $button
      </div>
    ";
  }

  public static function createSubscriberButton($con, $userToObj, $userLoggedInObj) {
    $userTo       = $userToObj->getUsername();
    $userLoggedIn = $userLoggedInObj->getUsername();

    $isSubscribedTo = $userLoggedInObj->isSubscribedTo($userTo);
    $buttonText     = $isSubscribedTo ? 'SUBSCRIBED' : 'SUBSCRIBE';
    $buttonText .= ' ' . $userToObj->getSubscriberCount();

    $buttonClass = $isSubscribedTo ? 'unsubscribe button' : 'subscribe button';
    $action      = "subscribe(\"$userTo\", \"$userLoggedIn\", this)";

    $button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);

    return "
      <div class='subscribeButtonContainer'>
        $button
      </div>
    ";
  }

}

?>