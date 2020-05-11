<?php

require_once 'includes/header.php';
require_once 'includes/classes/TrendingProvider.php';

$trendingProvider = new TrendingProvider($con, $userLoggedInObj);
$videos           = $trendingProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class='container-fluid'>
    <div class="largeVideoGridContaine">
        <?php if (sizeof($videos) > 0): ?>
            <?php echo $videoGrid->createLarge($videos, 'Trending videos uploaded in the last week', false); ?>
        <?php else: ?>
            <h1 class='display-4 my-3'>No trending videos to show</h1>
        <?php endif;?>
    </div>
</div>

<?php require_once 'includes/footer.php';?>
