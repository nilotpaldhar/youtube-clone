<?php
require_once 'includes/header.php';
require_once 'includes/classes/VideoPlayer.php';
require_once 'includes/classes/VideoInfoSection.php';
require_once 'includes/classes/Comment.php';
require_once 'includes/classes/CommentSection.php';

if (!isset($_GET['id'])) {
  header('Location: index.php');
  exit();
}

$video = new Video($con, $_GET['id'], $userLoggedInObj);
$video->incrementViews();
?>
<script src="assets/js/videoPlayerActions.js"></script>
<script src="assets/js/commentActions.js"></script>

 <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <?php $videoPlayer = new VideoPlayer($video);?>
        <?php echo $videoPlayer->create(true); ?>

        <?php $VideoInfoSection = new VideoInfoSection($con, $video, $userLoggedInObj);?>
        <?php echo $VideoInfoSection->create(); ?>

        <?php $commentSection = new CommentSection($con, $video, $userLoggedInObj);?>
        <?php echo $commentSection->create(); ?>
      </div>
      <div class="col-md-4"></div>
    </div>
  </div>


<?php require_once 'includes/footer.php';?>
