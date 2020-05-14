<?php

class SelectThumbnail {

  private $con, $video;

  public function __construct($con, $video) {
    $this->con   = $con;
    $this->video = $video;
  }

  public function create() {
    $thumbnailData = $this->getThumbnailData();
    $html          = '';

    foreach ($thumbnailData as $data) {
      $html .= $this->createThumbnailItem($data);
    }

    return "
        <div class='thumbnailsItemsContainer'>
            $html
        </div>
    ";
  }

  private function getThumbnailData() {
    $data = [];

    $query = $this->con->prepare("SELECT * FROM thumbnails WHERE videoid = :videoid");
    $query->bindParam(':videoid', $videoId);
    $videoId = $this->video->getId();
    $query->execute();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      array_push($data, $row);
    }

    return $data;
  }

  private function createThumbnailItem($data) {
    $id       = $data['id'];
    $url      = $data['filePath'];
    $videoId  = $data['videoid'];
    $selected = ($data['selected'] == 1) ? 'selected' : '';

    return "
        <div class='thumbnailItem $selected' onclick='setNewThumbnail($id, $videoId, this)'>
            <img src='$url'>
        </div>
    ";
  }
}

?>