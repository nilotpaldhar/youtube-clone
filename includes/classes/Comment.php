<?php

require_once 'ButtonProvider.php';
require_once 'CommentControls.php';

class Comment {

  private $con, $sqlData, $userLoggedInObj, $videoId;

  public function __construct($con, $input, $userLoggedInObj, $videoId) {
    $this->con             = $con;
    $this->userLoggedInObj = $userLoggedInObj;
    $this->videoId         = $videoId;

    // Check to see if input is an array or id
    if (is_array($input)) {
      $this->sqlData = $input;
    } else {
      $query = $this->con->prepare('SELECT * FROM comments WHERE id = :id');
      $query->bindParam(':id', $input);
      $query->execute();
      $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }
  }

  public function create() {
    $id            = $this->getId();
    $videoId       = $this->getVideoId();
    $body          = $this->sqlData['body'];
    $postedBy      = $this->sqlData['postedBy'];
    $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);
    $timestamp     = $this->convertTimestampToAgo($this->sqlData['datePosted']);

    $commentControls = new CommentControls($this->con, $this, $this->userLoggedInObj);
    $commentControls = $commentControls->create();

    $numResponses    = $this->getNumberOfReplies();
    $viewRepliesText = '';

    if ($numResponses > 0) {
      $viewRepliesText = "
        <span class='repliesSection viewReplies' onclick='getReplies(this, $id, $videoId)'>
          View all $numResponses replies
        </span>
      ";
    } else {
      $viewRepliesText = "<div class='repliesSection'></div>";
    }

    return "
        <div class='itemContainer'>
            <div class='comment'>
                $profileButton
                <div class='mainContainer'>
                    <div class='commentHeader'>
                        <a href='profile.php?username=$postedBy'>
                            <span class='username'>$postedBy</span>
                        </a>
                        <span class='timestamp'>$timestamp</span>
                    </div>

                    <div class='body'>
                        $body
                    </div>
                </div>
            </div>
            $commentControls
            $viewRepliesText
        </div>
    ";
  }

  public function convertTimestampToAgo($datetime, $full = false) {
    $now  = new DateTime;
    $ago  = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
    );
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) {
      $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

  public function getId() {
    return $this->sqlData['id'];
  }

  public function getVideoId() {
    return $this->videoId;
  }

  public function getNumberOfReplies() {
    $responseTo = $this->getId();
    $query      = $this->con->prepare("SELECT COUNT(*) as 'count' FROM comments WHERE responseTo = :responseTo");
    $query->bindParam(':responseTo', $responseTo);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
  }

  public function getLikes() {
    $commentId = $this->getId();

    // Get total likes
    $query = $this->con->prepare("SELECT COUNT(*) as 'count' FROM likes WHERE commentId = :commentId");
    $query->bindParam(':commentId', $commentId);
    $query->execute();

    $data     = $query->fetch(PDO::FETCH_ASSOC);
    $numLikes = $data['count'];

    // Get total dislikes
    $query = $this->con->prepare("SELECT COUNT(*) as 'count' FROM dislikes WHERE commentId = :commentId");
    $query->bindParam(':commentId', $commentId);
    $query->execute();

    $data        = $query->fetch(PDO::FETCH_ASSOC);
    $numDisLikes = $data['count'];

    return $numLikes - $numDisLikes;
  }

  public function wasLikedBy() {
    $id       = $this->getId();
    $username = $this->userLoggedInObj->getUsername();

    $query = $this->con->prepare("SELECT * FROM likes WHERE username = :username AND commentId = :commentId");
    $query->bindParam(':username', $username);
    $query->bindParam(':commentId', $id);
    $query->execute();

    return $query->rowCount() > 0;
  }

  public function wasDisLikedBy() {
    $id       = $this->getId();
    $username = $this->userLoggedInObj->getUsername();

    $query = $this->con->prepare("SELECT * FROM dislikes WHERE username = :username AND commentId = :commentId");
    $query->bindParam(':username', $username);
    $query->bindParam(':commentId', $id);
    $query->execute();

    return $query->rowCount() > 0;
  }

  public function like() {
    $id       = $this->getId();
    $username = $this->userLoggedInObj->getUsername();

    if ($this->wasLikedBy()) {
      $query = $this->con->prepare("DELETE FROM likes WHERE username = :username AND commentId = :commentId");
      $query->bindParam(':username', $username);
      $query->bindParam(':commentId', $id);
      $query->execute();

      return -1;

    } else {
      $query = $this->con->prepare("DELETE FROM dislikes WHERE username = :username AND commentId = :commentId");
      $query->bindParam(':username', $username);
      $query->bindParam(':commentId', $id);
      $query->execute();
      $count = $query->rowCount();

      $query = $this->con->prepare("INSERT INTO likes(username, commentId) VALUES(:username, :commentId)");
      $query->bindParam(':username', $username);
      $query->bindParam(':commentId', $id);
      $query->execute();

      return 1 + $count;
    }
  }

  public function disLike() {
    $id       = $this->getId();
    $username = $this->userLoggedInObj->getUsername();

    if ($this->wasDisLikedBy()) {
      $query = $this->con->prepare("DELETE FROM dislikes WHERE username = :username AND commentId = :commentId");
      $query->bindParam(':username', $username);
      $query->bindParam(':commentId', $id);
      $query->execute();

      return 1;

    } else {
      $query = $this->con->prepare("DELETE FROM likes WHERE username = :username AND commentId = :commentId");
      $query->bindParam(':username', $username);
      $query->bindParam(':commentId', $id);
      $query->execute();
      $count = $query->rowCount();

      $query = $this->con->prepare("INSERT INTO dislikes(username, commentId) VALUES(:username, :commentId)");
      $query->bindParam(':username', $username);
      $query->bindParam(':commentId', $id);
      $query->execute();

      return -1 - $count;
    }
  }

  public function getReplies() {
    $commentId = $this->getId();
    $videoId   = $this->getVideoId();

    $query = $this->con->prepare('SELECT * FROM comments WHERE responseTo = :commentId ORDER BY datePosted ASC');
    $query->bindParam(':commentId', $commentId);
    $query->execute();

    $comments = '';

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $comment = new Comment($this->con, $row, $this->userLoggedInObj, $videoId);
      $comments .= $comment->create();
    }

    return $comments;
  }
}

?>