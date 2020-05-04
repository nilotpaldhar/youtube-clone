<?php require_once 'includes/header.php';?>

<div class="container-fluid">
    <div class="videoSection">
        <?php $subscriptinsProvider = new SubscriptionsProvider($con, $userLoggedInObj);?>
        <?php $subscriptionVideos   = $subscriptinsProvider->getVideos();?>

        <?php $videoGrid = new VideoGrid($con, $userLoggedInObj);?>

        <?php if (User::isLoggedIn() && sizeof($subscriptionVideos) > 0): ?>
            <?php echo $videoGrid->create($subscriptionVideos, 'Subscriptions', false); ?>
        <?php endif;?>

        <?php echo $videoGrid->create(null, 'Recommended', false); ?>
    </div>
</div>

<?php require_once 'includes/footer.php';?>
