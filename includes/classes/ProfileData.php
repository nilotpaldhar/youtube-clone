<?php

class ProfileData {

  private $con, $profileUserObj;

  public function __construct($con, $profileUsername) {
    $this->con            = $con;
    $this->profileUserObj = new User($con, $profileUsername);
  }

  public function getProfileUserObj() {
    return $this->profileUserObj;
  }

  public function getProfileUsername() {
    return $this->profileUserObj->getUsername();
  }

  public function userExists() {
    $profileUsername = $this->getProfileUsername();
    $query           = $this->con->prepare('SELECT * FROM users WHERE username = :username');
    $query->bindParam(':username', $profileUsername);
    $query->execute();

    return $query->rowCount() != 0;
  }

  public function getCoverPhoto() {
    return "assets/images/coverPhotos/default-cover-photo.jpg";
  }

  public function getProfileUserFullName() {
    return $this->profileUserObj->getFullName();
  }

  public function getProfilePic() {
    return $this->profileUserObj->getProfilePic();
  }

  public function getSubscriberCount() {
    return $this->profileUserObj->getSubscriberCount();
  }

  public function getUsersVideos() {
    $videos = [];

    $query = $this->con->prepare('SELECT * FROM videos WHERE uploadedBy = :uploadedBy ORDER BY uploadDate DESC');
    $query->bindParam(':uploadedBy', $username);
    $username = $this->getProfileUsername();
    $query->execute();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $video = new Video($this->con, $row, $this->profileUserObj->getUsername());
      array_push($videos, $video);
    }

    return $videos;
  }

  public function getTotalViews() {
    $query = $this->con->prepare('SELECT SUM(views) FROM videos WHERE uploadedBy = :uploadedBy');
    $query->bindParam(':uploadedBy', $username);
    $username = $this->getProfileUsername();
    $query->execute();

    return $query->fetchColumn();
  }

  public function getSignUpDate() {
    $date = $this->profileUserObj->getSignUpDate();
    return date('F jS, Y', strtotime($date));
  }

  public function getAllUserDetails() {
    return [
      "Name"         => $this->getProfileUserFullName(),
      "Username"     => $this->getProfileUsername(),
      "Subscribers"  => $this->getSubscriberCount(),
      "Total Views"  => $this->getTotalViews(),
      "Sign Up Date" => $this->getSignUpDate(),
    ];
  }

}

?>