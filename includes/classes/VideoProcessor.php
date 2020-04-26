<?php

class VideoProcessor {

  private $con;
  private $sizeLimit   = 500000000; // 500mb
  private $allowedType = ['mp4', 'flv', 'webm', 'mkv', 'vob', 'ogv', 'ogg', 'avi', 'wmv', 'mov', 'mpeg', 'mpg'];
  private $ffmpegPath;

  public function __construct($con) {
    $this->con        = $con;
    $this->ffmpegPath = realpath('ffmpeg/bin/ffmpeg.exe');
  }

  public function upload($videoUploadData) {
    $targetDir = 'uploads/videos/';
    $videoData = $videoUploadData->getVideoDataArray();

    // Temporary path of video
    $tempFilePath = $targetDir . uniqid() . basename($videoData['name']);

    // Replacing whitespace
    $tempFilePath = str_replace(' ', '_', $tempFilePath);

    // Validating file data
    $isValidData = $this->processData($videoData, $tempFilePath);

    if (!$isValidData) {
      return false;
    }

    if (move_uploaded_file($videoData['tmp_name'], $tempFilePath)) {
      $finalFilePath = $targetDir . uniqid() . '.mp4';
      if (!$this->insertVideoData($videoUploadData, $finalFilePath)) {
        echo 'Insert query failed';
        return false;
      }

      if (!$this->convertVideoToMp4($tempFilePath, $finalFilePath)) {
        echo 'Upload failed';
        return false;
      }

      if (!$this->deleteFile($tempFilePath)) {
  echo 'Upload failed';
  return false;
}

    }
  }

  private function processData($videoData, $filePath) {
    $videoType = pathinfo($filePath, PATHINFO_EXTENSION);
    if (!$this->isValidSize($videoData)) {
      echo 'File too large. Cant\'t be more than ' . $this->sizeLimit . 'bytes';
      return false;
    } else if (!$this->isValidType($videoType)) {
      echo 'File  format is not supported';
      return false;
    } else if ($this->hasError($videoData)) {
      echo 'Error code: ' . $videoData['error'];
      return false;
    }
    return true;
  }

  private function isValidSize($data) {
    return $data['size'] <= $this->sizeLimit;
  }

  private function isValidType($type) {
    $lowercased = strtolower($type);
    return in_array($lowercased, $this->allowedType);
  }

  private function hasError($data) {
    return $data['error'] != 0;
  }

  private function insertVideoData($uploadData, $filePath) {
    // Extracting video data into variables
    $title       = $uploadData->getTitle();
    $uploadBy    = $uploadData->getUploadedBy();
    $description = $uploadData->getDescription();
    $privacy     = $uploadData->getPrivacy();
    $category    = $uploadData->getCategory();

    $query = $this->con->prepare("INSERT INTO videos(title, uploadedBy, description, privacy, filePath, category) VALUES(:title, :uploadedBy, :description, :privacy, :filePath, :category)");
    $query->bindParam(":title", $title);
    $query->bindParam(":uploadedBy", $uploadBy);
    $query->bindParam(":description", $description);
    $query->bindParam(":privacy", $privacy);
    $query->bindParam(":filePath", $filePath);
    $query->bindParam(":category", $category);

    return $query->execute();
  }

  public function convertVideoToMp4($tempFilePath, $finalFilePath) {
    $cmd = "{$this->ffmpegPath} -i $tempFilePath $finalFilePath 2>&1";

    $outputLog = [];
    exec($cmd, $outputLog, $returnCode);

    if ($returnCode != 0) {
      // Command failed
      foreach ($outputLog as $line) {
        echo $line . '<br>';
      }
      return false;
    }

    return true;
  }

  private function deleteFile($filePath) {
    if (!unlink($filePath)) {
      echo 'Could not delete file\n';
      return false;
    }
    return true;
  }

}

?>