<?php
require_once '../includes/config.php';

if (isset($_POST['thumbnailId']) && isset($_POST['videoId'])) {
  $videoId     = $_POST['videoId'];
  $thumbnailId = $_POST['thumbnailId'];

  $query = $con->prepare("UPDATE thumbnails SET selected = 0 WHERE videoid = :videoid");
  $query->bindParam(':videoid', $videoId);
  $query->execute();

  $query = $con->prepare("UPDATE thumbnails SET selected = 1 WHERE id = :id");
  $query->bindParam(':id', $thumbnailId);
  $query->execute();

} else {
  echo 'One or more parameters are not passed into updateTumbnail.php file';
}

?>