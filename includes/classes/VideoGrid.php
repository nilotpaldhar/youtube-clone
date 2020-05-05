<?php

class VideoGrid {
  private $con, $userLoggedInObj;
  private $largeMode = false;
  private $gridClass = 'videoGrid';

  public function __construct($con, $userLoggedInObj) {
    $this->con             = $con;
    $this->userLoggedInObj = $userLoggedInObj;
  }

  public function create($videos, $title, $showFilter) {

    if ($videos == null) {
      $gridItems = $this->generateItems();
    } else {
      $gridItems = $this->generateItemsFromVideos($videos);
    }

    $header = '';

    if ($title != null) {
      $header = $this->createGridHeader($title, $showFilter);
    }

    return "
        $header
        <div class='$this->gridClass'>
            $gridItems
        </div>
    ";
  }

  public function generateItems() {
    $query = $this->con->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
    $query->execute();

    $elementsHtml = '';

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $video = new Video($this->con, $row, $this->userLoggedInObj);
      $item  = new VideoGridItem($video, $this->largeMode);
      $elementsHtml .= $item->create();
    }

    return $elementsHtml;
  }

  public function generateItemsFromVideos($videos) {
    $elementsHtml = '';

    foreach ($videos as $video) {
      $item = new VideoGridItem($video, $this->largeMode);
      $elementsHtml .= $item->create();
    }

    return $elementsHtml;
  }

  public function createGridHeader($title, $showFilter) {
    $filter = '';

    if ($showFilter) {
      $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? $link = "https" : $link = "http";
      // Here append the common URL characters.
      $link .= "://";
      // Append the host(domain name, ip) to the URL.
      $link .= $_SERVER['HTTP_HOST'];
      // Append the requested resource location to the URL
      $link .= $_SERVER['REQUEST_URI'];

      // Parsing url into an array
      $urlArray = parse_url($link);
      // Get the query from the url
      $query = $urlArray['query'];
      // Parse the query into string
      parse_str($query, $params);
      unset($params['orderBy']);

      $newQuery = http_build_query($params);
      $newUrl   = basename($_SERVER["PHP_SELF"]) . '?' . $newQuery;

      // Set up filter
      $filter = "
        <div class='right'>
          <span>Order by:</span>
          <a href='$newUrl&orderBy=uploadDate'>Upload Date</a>
          <a href='$newUrl&orderBy=views'>Most Viewed</a>
        </div>
      ";
    }

    return "
      <div class='videoGridHeader'>
        <div class='left'>$title</div>
        $filter
      </div>
    ";
  }

  public function createLarge($videos, $title, $showFilter) {
    $this->gridClass .= ' large';
    $this->largeMode = true;
    return $this->create($videos, $title, $showFilter);
  }

}

?>