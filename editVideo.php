<?php
require_once 'includes/header.php';
require_once 'includes/classes/VideoPlayer.php';
require_once 'includes/classes/VideoInfoSection.php';
require_once 'includes/classes/VideoUploadData.php';
require_once 'includes/classes/VideoDetailsFormProvider.php';
require_once 'includes/classes/SelectThumbnail.php';

if (!User::isLoggedIn()) {
  header('Location: signin.php');
}

if (!isset($_GET['videoId']) || $_GET['videoId'] == '') {
  echo "<h1 class='display-4 my-3'>No video Selected</h1>";
  exit();
} else {
  $videoId = $_GET['videoId'];
}

$video          = new Video($con, $videoId, $userLoggedInObj);
$detailsMessage = null;

if ($video->getUploadedBy() != $userLoggedInObj->getUsername()) {
  echo "<h1 class='display-4 my-3'>Not your video</h1>";
  exit();
}

if (isset($_POST['saveButton'])) {
  $title       = $_POST['titleInput'];
  $description = $_POST['descriptionInput'];
  $category    = $_POST['categoryInput'];
  $privacy     = $_POST['privacyInput'];
  $uploadedBy  = $userLoggedInObj->getUsername();

  $videoData = new VideoUploadData(null, $title, $description, $category, $privacy, $uploadedBy);

  if ($videoData->updateDetails($con, $video->getId())) {
    $detailsMessage = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>SUCESS!</strong> Details updated successfully.
  					<button type = "button" class= "close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button >
				</div >
    ';
    $video = new Video($con, $videoId, $userLoggedInObj);
  } else {
    $detailsMessage = '
       <div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>FAILED!</strong> Something  went wrong.
  					<button type = "button" class= "close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button >
				</div >
    ';
  }
}
?>

<script src="assets/js/editVideoActions.js"></script>

<div class="editVideoContainer column" id='videoContainer'>
  <?php echo $detailsMessage; ?>
  <div class="container-fluid">
        <div class="topSection">
            <div class="row">
                <div class="col-md-9">
                    <?php $videoPlayer = new VideoPlayer($video);?>
                    <?php echo $videoPlayer->create(false); ?>
                </div>
                <div class="col-md-3 mt-sm-3 mt-md-0">
                    <?php $selectThumbnail = new SelectThumbnail($con, $video);?>
                    <?php echo $selectThumbnail->create(); ?>
                </div>
            </div>
        </div>
        <div class="bottomSection mt-5">
            <div class="row">
                <div class="col-md-9">
                  <?php $formProvider = new VideoDetailsFormProvider($con);?>
                  <?php echo $formProvider->createEditDetailsdForm($video); ?>
                </div>
            </div>
        </div>
  </div>
</div>


<?php require_once 'includes/footer.php';?>
