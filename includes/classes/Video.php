<?php

class Video {
  private $con, $sqlData, $userLoggedInObj;

  public function __construct($con, $input, $userLoggedInObj) {
    $this->con             = $con;
    $this->userLoggedInObj = $userLoggedInObj;

    // Check to see if input is an array or id
    if (is_array($input)) {
      $this->sqlData = $input;
    } else {
      $query = $this->con->prepare('SELECT * FROM videos WHERE id = :id');
      $query->bindParam(':id', $input);
      $query->execute();
      $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }
  }

  public function getId() {
    if (!isset($this->sqlData['id'])) {
      return;
    }
    return $this->sqlData['id'];
  }

  public function getUploadedBy() {
    if (!isset($this->sqlData['uploadedBy'])) {
      return;
    }
    return $this->sqlData['uploadedBy'];
  }

  public function getTitle() {
    if (!isset($this->sqlData['title'])) {
      return;
    }
    return $this->sqlData['title'];
  }

  public function getDescription() {
    if (!isset($this->sqlData['description'])) {
      return;
    }
    return $this->sqlData['description'];
  }

  public function getPrivacy() {
    if (!isset($this->sqlData['privacy'])) {
      return;
    }
    return $this->sqlData['privacy'];
  }

  public function getFilePath() {
    if (!isset($this->sqlData['filePath'])) {
      return;
    }
    return $this->sqlData['filePath'];
  }

  public function getCategory() {
    if (!isset($this->sqlData['category'])) {
      return;
    }
    return $this->sqlData['category'];
  }

  public function getUploadedDate() {
    if (!isset($this->sqlData['uploadDate'])) {
      return;
    }
    $date = $this->sqlData['uploadDate'];
    return date('M j, Y', strtotime($date));
  }

  public function getTimeStamp() {
    if (!isset($this->sqlData['uploadDate'])) {
      return;
    }
    $date = $this->sqlData['uploadDate'];
    return date('M jS, Y', strtotime($date));
  }

  public function getViews() {
    if (!isset($this->sqlData['views'])) {
      return;
    }
    return $this->sqlData['views'];
  }

  public function getDuration() {
    if (!isset($this->sqlData['duration'])) {
      return;
    }
    return $this->sqlData['duration'];
  }

  public function isExist($id) {
    $query = $this->con->prepare("SELECT id FROM videos WHERE id = :id");
    $query->bindParam(':id', $id);
    $query->execute();

    return ($query->fetchColumn() > 0) ? true : false;
  }

  public function incrementViews() {
    $query = $this->con->prepare("UPDATE videos SET views = views + 1 WHERE id = :id");
    $query->bindParam(':id', $videoId);
    $videoId = $this->getId();
    $query->execute();
    $this->sqlData['views'] = $this->sqlData['views'] + 1;
  }

  public function getLikes() {
    $query = $this->con->prepare("SELECT COUNT(*) as 'count' FROM likes WHERE videoId = :videoId");
    $query->bindParam(':videoId', $videoId);
    $videoId = $this->getId();
    $query->execute();

    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data['count'];
  }

  public function getDisLikes() {
    $query = $this->con->prepare("SELECT COUNT(*) as 'count' FROM dislikes WHERE videoId = :videoId");
    $query->bindParam(':videoId', $videoId);
    $videoId = $this->getId();
    $query->execute();

    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data['count'];
  }

  public function like() {
    $id       = $this->getId();
    $username = $this->userLoggedInObj->getUsername();

    if ($this->wasLikedBy()) {
      $query = $this->con->prepare("DELETE FROM likes WHERE username = :username AND videoID = :videoId");
      $query->bindParam(':username', $username);
      $query->bindParam(':videoId', $id);
      $query->execute();

      $result = ['likes' => -1, 'disLikes' => 0];
      return json_encode($result);

    } else {
      $query = $this->con->prepare("DELETE FROM dislikes WHERE username = :username AND videoID = :videoId");
      $query->bindParam(':username', $username);
      $query->bindParam(':videoId', $id);
      $query->execute();
      $count = $query->rowCount();

      $query = $this->con->prepare("INSERT INTO likes(username, videoId) VALUES(:username, :videoId)");
      $query->bindParam(':username', $username);
      $query->bindParam(':videoId', $id);
      $query->execute();

      $result = ['likes' => 1, 'disLikes' => 0 - $count];
      return json_encode($result);
    }
  }

  public function disLike() {
    $id       = $this->getId();
    $username = $this->userLoggedInObj->getUsername();

    if ($this->wasDisLikedBy()) {
      $query = $this->con->prepare("DELETE FROM dislikes WHERE username = :username AND videoID = :videoId");
      $query->bindParam(':username', $username);
      $query->bindParam(':videoId', $id);
      $query->execute();

      $result = ['likes' => 0, 'disLikes' => -1];
      return json_encode($result);

    } else {
      $query = $this->con->prepare("DELETE FROM likes WHERE username = :username AND videoID = :videoId");
      $query->bindParam(':username', $username);
      $query->bindParam(':videoId', $id);
      $query->execute();
      $count = $query->rowCount();

      $query = $this->con->prepare("INSERT INTO dislikes(username, videoId) VALUES(:username, :videoId)");
      $query->bindParam(':username', $username);
      $query->bindParam(':videoId', $id);
      $query->execute();

      $result = ['likes' => 0 - $count, 'disLikes' => 1];
      return json_encode($result);
    }
  }

  public function wasLikedBy() {
    $id       = $this->getId();
    $username = $this->userLoggedInObj->getUsername();

    $query = $this->con->prepare("SELECT * FROM likes WHERE username = :username AND videoId = :videoId");
    $query->bindParam(':username', $username);
    $query->bindParam(':videoId', $id);
    $query->execute();

    return $query->rowCount() > 0;
  }

  public function wasDisLikedBy() {
    $id       = $this->getId();
    $username = $this->userLoggedInObj->getUsername();

    $query = $this->con->prepare("SELECT * FROM dislikes WHERE username = :username AND videoId = :videoId");
    $query->bindParam(':username', $username);
    $query->bindParam(':videoId', $id);
    $query->execute();

    return $query->rowCount() > 0;
  }

  public function getNumberOfComments() {
    $query = $this->con->prepare('SELECT * FROM comments WHERE videoId = :videoId');
    $query->bindParam(':videoId', $videoId);
    $videoId = $this->getId();
    $query->execute();

    return $query->rowCount();
  }

  public function getComments() {
    $query = $this->con->prepare('SELECT * FROM comments WHERE videoId = :videoId AND responseTo = 0 ORDER BY datePosted DESC');
    $query->bindParam(':videoId', $videoId);
    $videoId = $this->getId();
    $query->execute();

    $comments = [];

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $comment = new Comment($this->con, $row, $this->userLoggedInObj, $videoId);
      array_push($comments, $comment);
    }

    return $comments;
  }

  public function getThumbnail() {
    $query = $this->con->prepare("SELECT filePath FROM thumbnails WHERE videoId = :videoId AND selected = 1");
    $query->bindParam(':videoId', $videoId);
    $videoId = $this->getId();
    $query->execute();

    return $query->fetchColumn();
  }

}

?>