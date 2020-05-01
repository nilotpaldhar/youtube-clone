<?php

require_once 'includes/classes/ButtonProvider.php';

class VideoInfoControls {
  private $video, $userLoggedInObj;

  public function __construct($video, $userLoggedInObj) {
    $this->video           = $video;
    $this->userLoggedInObj = $userLoggedInObj;
  }

  public function create() {
    $likeButton    = $this->createLikeButton();
    $disLikeButton = $this->createDisLikeButton();
    return "
    <div class='controls'>
        $likeButton
        $disLikeButton
    </div>
    ";
  }

  private function createLikeButton() {
    $text    = $this->video->getLikes();
    $videoId = $this->video->getId();
    $action  = "likeVideo(this, $videoId)";
    $imgSrc  = 'assets/images/icons/thumb-up.png';

    if ($this->video->wasLikedBy()) {
      $imgSrc = 'assets/images/icons/thumb-up-active.png';
    }

    return ButtonProvider::createButton($text, $imgSrc, $action, 'likeButton');
  }

  private function createDisLikeButton() {
    $text    = $this->video->getDisLikes();
    $videoId = $this->video->getId();
    $action  = "disLikeVideo(this, $videoId)";
    $imgSrc  = 'assets/images/icons/thumb-down.png';

    if ($this->video->wasDisLikedBy()) {
      $imgSrc = 'assets/images/icons/thumb-down-active.png';
    }

    return ButtonProvider::createButton($text, $imgSrc, $action, 'disLikeButton');
  }
}
?>