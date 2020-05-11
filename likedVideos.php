<?php

require_once 'includes/header.php';
require_once 'includes/classes/LikedVideosProvider.php';

if (!User::isLoggedIn()) {
  header('Location: signin.php');
}

$likedVideosProvider = new LikedVideosProvider($con, $userLoggedInObj);
$videos              = $likedVideosProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class='container-fluid'>
    <div class="largeVideoGridContaine">
        <?php if (sizeof($videos) > 0): ?>
            <?php echo $videoGrid->createLarge($videos, 'Videos that you have liked', false); ?>
        <?php else: ?>
            <h1 class='display-4 my-3'>No videos to show</h1>
        <?php endif;?>
    </div>
</div>

<?php require_once 'includes/footer.php';?>
