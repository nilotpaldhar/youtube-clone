<?php
include_once 'includes/header.php';
include_once 'includes/classes/SearchResultsProvider.php';

if (!isset($_GET['term']) || $_GET['term'] == '') {
  echo "<h4 class='my-3'>Please enter a search term</h4>";
  exit();
}

$term = $_GET['term'];

if (!isset($_GET['orderBy']) || $_GET['orderBy'] == 'views') {
  $orderBy = 'views';
} else {
  $orderBy = 'uploadDate';
}

$searchResultsProvider = new SearchResultsProvider($con, $userLoggedInObj);
$videos                = $searchResultsProvider->getVideos($term, $orderBy);

$videoGrid = new VideoGrid($con, $userLoggedInObj);
$numVideos = (sizeof($videos) > 1) ? sizeof($videos) . ' results found' : sizeof($videos) . ' result found';
?>

<div class='container-fluid'>
    <div class="largeVideoGridContaine">
        <?php if (sizeof($videos) > 0): ?>
            <?php echo $videoGrid->createLarge($videos, $numVideos, true); ?>
        <?php else: ?>
            <h1 class='display-4 my-3'>No Results Found</h1>
        <?php endif;?>
    </div>
</div>

<?php include_once 'includes/footer.php';?>
