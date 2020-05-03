<?php

require_once 'ButtonProvider.php';

class CommentControls {
  private $con, $comment, $userLoggedInObj;

  public function __construct($con, $comment, $userLoggedInObj) {
    $this->con             = $con;
    $this->comment         = $comment;
    $this->userLoggedInObj = $userLoggedInObj;
  }

  public function create() {
    $replyButton   = $this->createReplyButton();
    $likesCount    = $this->createLikesCount();
    $likeButton    = $this->createLikeButton();
    $disLikeButton = $this->createDisLikeButton();
    $replySection  = $this->createReplySection();

    return "
      <div class='controls'>
        $replyButton
        $likesCount
        $likeButton
        $disLikeButton
      </div>
      $replySection
    ";
  }

  private function createReplyButton() {
    $text   = 'REPLY';
    $action = 'toggleReply(this)';

    return ButtonProvider::createButton($text, null, $action, null);
  }

  private function createLikesCount() {
    $text = $this->comment->getLikes();
    if ($text == 0) {
      $text = '';
    }

    return "
        <span class='likesCount'>$text</span>
    ";
  }

  private function createLikeButton() {
    $videoId   = $this->comment->getVideoId();
    $commentId = $this->comment->getId();
    $action    = "likeComment(this, $commentId, $videoId)";
    $imgSrc    = 'assets/images/icons/thumb-up.png';

    if ($this->comment->wasLikedBy()) {
      $imgSrc = 'assets/images/icons/thumb-up-active.png';
    }

    return ButtonProvider::createButton('', $imgSrc, $action, 'likeButton');
  }

  private function createDisLikeButton() {
    $videoId   = $this->comment->getVideoId();
    $commentId = $this->comment->getId();
    $action    = "disLikeComment(this, $commentId, $videoId)";
    $imgSrc    = 'assets/images/icons/thumb-down.png';

    if ($this->comment->wasDisLikedBy()) {
      $imgSrc = 'assets/images/icons/thumb-down-active.png';
    }

    return ButtonProvider::createButton('', $imgSrc, $action, 'disLikeButton');
  }

  private function createReplySection() {
    $postedBy  = $this->userLoggedInObj->getUsername();
    $videoId   = $this->comment->getVideoId();
    $commentId = $this->comment->getId();

    $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);

    $cancelButtonAction = "toggleReply(this)";
    $cancelButton       = ButtonProvider::createButton('Cancel', null, $cancelButtonAction, 'cancelComment');

    $postButtonAction = "postComment(this, \"$postedBy\", $videoId, $commentId, \"repliesSection\")";
    $postButton       = ButtonProvider::createButton('Reply', null, $postButtonAction, 'postComment');

    return "
      <div class='commentForm hidden'>
        $profileButton
        <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
        $cancelButton
        $postButton
      </div>
    ";
  }

}
?>