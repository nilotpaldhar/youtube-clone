<?php

class VideoProcessor {

  private $con;
  private $sizeLimit   = 500000000; // 500mb
  private $allowedType = ['mp4', 'flv', 'webm', 'mkv', 'vob', 'ogv', 'ogg', 'avi', 'wmv', 'mov', 'mpeg', 'mpg'];
  private $ffmpegPath;
  private $ffprovePath;

  public function __construct($con) {
    $this->con         = $con;
    $this->ffmpegPath  = realpath('ffmpeg/bin/ffmpeg.exe');
    $this->ffprovePath = realpath('ffmpeg/bin/ffprobe.exe');
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
        echo 'Upload failed: Failed to convert video\n';
        return false;
      }

      if (!$this->deleteFile($tempFilePath)) {
        echo 'Upload failed: Failed to delete file\n';
        return false;
      }

      if (!$this->generateThumbnails($finalFilePath)) {
        echo 'Upload failed: Failed to generate thumbnail\n';
        return false;
      }
    }

    return true;
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

  public function generateThumbnails($filePath) {
    $thumbnailSize    = '210x118';
    $thumbnails       = 3;
    $patchToThumbnail = 'uploads/videos/thumbnails';

    $duration = $this->getVideoDuration($filePath);

    $videoId = $this->con->lastInsertId();
    $this->updateDuration($duration, $videoId);

    for ($num = 1; $num <= $thumbnails; $num++) {
      $imageName         = uniqid() . '.jpg';
      $interval          = ($duration * 0.8) / $thumbnails * $num;
      $fullThumbnailPath = "$patchToThumbnail/$videoId-$imageName";

      $cmd = "{$this->ffmpegPath} -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";

      $outputLog = [];
      exec($cmd, $outputLog, $returnCode);

      if ($returnCode != 0) {
        // Command failed
        foreach ($outputLog as $line) {
          echo $line . '<br>';
        }
      }

      $query = $this->con->prepare("INSERT INTO thumbnails(videoId, filePath, selected) VALUES(:videoId, :filePath, :selected)");
      $query->bindParam(':videoId', $videoId);
      $query->bindParam(':filePath', $fullThumbnailPath);
      $query->bindParam(':selected', $selected);

      $selected = $num == 1 ? 1 : 0;

      $success = $query->execute();

      if (!$success) {
        echo 'Error inserting thumbnail\n';
        return false;
      }
    }

    return true;
  }

  private function getVideoDuration($filePath) {
    return (int) shell_exec("{$this->ffprovePath} -v error -select_streams v:0 -show_entries stream=duration -of default=noprint_wrappers=1:nokey=1 {$filePath}");
  }

  private function updateDuration($duration, $videoId) {
    $duration = (int) $duration;
    $hours    = floor($duration / 3600);
    $mins     = floor(($duration - ($hours * 3600)) / 60);
    $secs     = floor($duration % 60);

    $hours = ($hours < 1) ? '' : $hours . ':';
    $mins  = ($mins < 10) ? '0' . $mins . ':' : $mins . ':';
    $secs  = ($secs < 10) ? '0' . $secs : $secs;

    $duration = $hours . $mins . $secs;

    $query = $this->con->prepare('UPDATE videos SET duration = :duration WHERE id = :videoId');
    $query->bindParam(':duration', $duration);
    $query->bindParam(':videoId', $videoId);
    $query->execute();
  }
}

?>