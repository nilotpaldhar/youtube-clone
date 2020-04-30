<?php
require_once 'includes/header.php';
require_once 'includes/classes/VideoPlayer.php';

if (!isset($_GET['id'])) {
  header('Location: index.php');
  exit();
}

$video = new Video($con, $_GET['id'], $userLoggedInObj);
$video->incrementViews();
?>

 <div class="container-fluid">
    <div class="row">
      <div class="col-md-9">
        <?php $videoPlayer = new VideoPlayer($video);?>
        <?php echo $videoPlayer->create(true); ?>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>

<!-- <div class='watchLeftColumn'></div>
<div class="suggestions"></div> -->

<?php require_once 'includes/footer.php';?>
