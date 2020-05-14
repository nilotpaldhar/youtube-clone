<?php

class VideoUploadData {

  private $videoDataArray, $title, $description, $category, $privacy, $uploadedBy;

  public function __construct($videoDataArray, $title, $description, $category, $privacy, $uploadedBy) {
    $this->videoDataArray = $videoDataArray;
    $this->title          = $title;
    $this->description    = $description;
    $this->category       = $category;
    $this->privacy        = $privacy;
    $this->uploadedBy     = $uploadedBy;
  }

  public function getVideoDataArray() {
    return $this->videoDataArray;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getCategory() {
    return $this->category;
  }

  public function getPrivacy() {
    return $this->privacy;
  }

  public function getUploadedBy() {
    return $this->uploadedBy;
  }

  public function updateDetails($con, $videoId) {
    $title       = $this->getTitle();
    $description = $this->getDescription();
    $category    = $this->getCategory();
    $privacy     = $this->getPrivacy();

    $query = $con->prepare("UPDATE videos SET title = :title, description = :description, category = :category, privacy = :privacy WHERE id = :id");
    $query->bindParam(':title', $title);
    $query->bindParam(':description', $description);
    $query->bindParam(':category', $category);
    $query->bindParam(':privacy', $privacy);
    $query->bindParam(':id', $videoId);

    return $query->execute();

  }
}

?>