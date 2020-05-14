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

  public function createEditDetailsdForm($video) {
    $titleInput       = $this->createTitleInput($video->getTitle());
    $descriptionInput = $this->createDescriptionInput($video->getDescription());
    $categoriesInput  = $this->createCategoriesInput($video->getCategory());
    $privacyInput     = $this->createPrivacyInput($video->getPrivacy());
    $savedBtn         = $this->createSaveButton();

    return "
    <form method='POST'>
        $titleInput
        $descriptionInput
        $categoriesInput
        $privacyInput
         $savedBtn
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

  private function createTitleInput($value = null) {
    $value = ($value == null) ? '' : $value;
    return "
    <div class='form-group'>
        <input type='text' class='form-control' name='titleInput' placeholder='Title' value='$value' required>
    </div>
    ";
  }

  private function createDescriptionInput($value = null) {
    $value = ($value == null) ? '' : $value;

    return "
    <div class='form-group'>
        <textarea class='form-control' name='descriptionInput' rows='3' placeholder='Description' required>$value</textarea>
    </div>
    ";
  }

  private function createPrivacyInput($value = null) {
    $value = ($value == null) ? '' : $value;

    $privateSelected = ($value == 0) ? 'selected=selected' : '';
    $publicSelected  = ($value == 1) ? 'selected=selected' : '';

    return "
    <div class='form-group'>
        <select class='form-control' name='privacyInput'>
            <option value='0' $privateSelected>Private</option>
            <option value='1' $publicSelected>Public</option>
        </select>
    </div>
    ";
  }

  private function createCategoriesInput($value = null) {
    $value = ($value == null) ? '' : $value;

    $query = $this->con->prepare('SELECT * FROM category');
    $query->execute();

    $html = "
    <div class='form-group'>
        <select class='form-control' name='categoryInput'>";

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $id       = $row['id'];
      $name     = $row['name'];
      $selected = ($value == $id) ? 'selected=selected' : '';

      $html .= "<option value='$id' $selected>$name</option>";
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

  private function createSaveButton() {
    return "<button type='submit' name='saveButton' class='btn btn-primary'>Save</button>";
  }

}

?>