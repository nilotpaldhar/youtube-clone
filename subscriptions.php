<?php

require_once 'includes/header.php';

if (!User::isLoggedIn()) {
  header('Location: signin.php');
}

$subscriptionsProvider = new SubscriptionsProvider($con, $userLoggedInObj);
$videos                = $subscriptionsProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class='container-fluid'>
    <div class="largeVideoGridContaine">
        <?php if (sizeof($videos) > 0): ?>
            <?php echo $videoGrid->createLarge($videos, 'Videos from your subscription', false); ?>
        <?php else: ?>
            <h1 class='display-4 my-3'>No videos to show</h1>
        <?php endif;?>
    </div>
</div>

<?php require_once 'includes/footer.php';?>
