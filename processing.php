<?php
require_once 'includes/header.php';
require_once 'includes/classes/VideoUploadData.php';
require_once 'includes/classes/VideoProcessor.php';

if (!User::isLoggedIn()) {
  header('Location: signin.php');
}

if (!isset($_POST['uploadButton'])) {
  echo 'No file sent to page';
  exit();
}
# Create file upload data
$videoUploadData = new VideoUploadData($_FILES['fileInput'], $_POST['titleInput'], $_POST['descriptionInput'], $_POST['categoryInput'], $_POST['privacyInput'], $userLoggedInObj->getUsername());

# Process video data (upload)
$videoProcessor = new VideoProcessor($con);
$wasSuccessfull = $videoProcessor->upload($videoUploadData);

# 3) Check if upload was successful
if ($wasSuccessfull) {
  echo 'Video uploaded successfully';
}

require_once 'includes/footer.php';
?>
