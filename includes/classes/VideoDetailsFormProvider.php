<?php

class VideoDetailsFormProvider {

  // Initailising database connection variable
  private $con;

  public function __construct($con) {
    $this->con = $con;
  }

  public function createUploadForm() {
    $fileInput        = $this->createFileInput();
    $titleInput       = $this->createTitleInput();
    $descriptionInput = $this->createDescriptionInput();
    $categoriesInput  = $this->createCategoriesInput();
    $privacyInput     = $this->createPrivacyInput();
    $uploadBtn        = $this->createUploadButton();

    return "
    <form action='processing.php' method='POST' enctype='multipart/form-data'>
        $fileInput
        $titleInput
        $descriptionInput
        $categoriesInput
        $privacyInput
         $uploadBtn
    </form>
    ";
  }

  private function createFileInput() {
    return "
    <div class='form-group'>
        <input type='file' class='form-control-file' name='fileInput' required>
    </div>
    ";
  }

  private function createTitleInput() {
    return "
    <div class='form-group'>
        <input type='text' class='form-control' name='titleInput' placeholder='Title' required>
    </div>
    ";
  }

  private function createDescriptionInput() {
    return "
    <div class='form-group'>
        <textarea class='form-control' name='descriptionInput' rows='3' placeholder='Description' required></textarea>
    </div>
    ";
  }

  private function createPrivacyInput() {
    return "
    <div class='form-group'>
        <select class='form-control' name='privacyInput'>
            <option value='0'>Private</option>
            <option value='1'>Public</option>
        </select>
    </div>
    ";
  }

  private function createCategoriesInput() {
    $query = $this->con->prepare('SELECT * FROM category');
    $query->execute();

    $html = "
    <div class='form-group'>
        <select class='form-control' name='categoryInput'>";

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $id   = $row['id'];
      $name = $row['name'];
      $html .= "<option value='$id'>$name</option>";
    }
    $html .= "
    </select>
    </div>
    ";
    return $html;
  }

  private function createUploadButton() {
    return "<button type='submit' name='uploadButton' class='btn btn-primary'>Upload</button>";
  }
}

?>