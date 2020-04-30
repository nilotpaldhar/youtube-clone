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
    return $this->sqlData['uploadDate'];
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

  public function incrementViews() {
    $query = $this->con->prepare("UPDATE videos SET views = views + 1 WHERE id = :id");
    $query->bindParam(':id', $videoId);
    $videoId = $this->getId();
    $query->execute();
    $this->sqlData['views'] = $this->sqlData['views'] + 1;
  }
}

?>