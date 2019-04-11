<?php ob_start(); ?>

<?php include "../lock.php"; ?>

<?php

$target_dir = "images/";

if (isset($_GET['delete'])) {
    unlink($_GET['delete']);
    $error = $_GET['delete']." was deleted!";
}

// Check if image file is a actual image or fake image
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $message = "File is an image - ".$check["mime"].".<br>";
        $uploadOk = 1;
    } else {
        $error = "File is not an image.<br>";
        $uploadOk = 0;
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $error = "Sorry, file already exists.<br>";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $error = "Sorry, your file is too large.<br>";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error = "Sorry, your file was not uploaded.<br>";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $message = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
        } else {
            $error = "Sorry, there was an error uploading your file.<br>";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<main role="main" class="flex-shrink-0">
<div class="container">

    <div class="row">
    <?php
    if ($handle = opendir($target_dir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                echo "<div class='col-sm-2'>";
                echo "<a href='?delete=".$target_dir.$entry."'>".$entry."</a><br>";
                echo "<img src='".$target_dir.$entry."' width='100' height='100'><br>";
                echo "</div>";
            }
        }
        closedir($handle);
    }
    ?>
    </div>

    <br>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="fileinput fileinput-new" data-provides="fileinput">
          <div class="fileinput-preview img-thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
          <div>
            <span class="btn btn-outline-secondary btn-file">
              <span class="fileinput-new">Select Image</span>
              <span class="fileinput-exists">Change</span>
              <input type="file" name="fileToUpload" required>
            </span>
            <a href="#" class="btn btn-outline-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
          </div>
        </div>
        <input type="submit" class="btn btn-outline-secondary" value="Upload Image" name="submit">
    </form>

    <?php if (isset($message)) { ?>
        <div class="alert alert-success" role="alert"><?php echo $message; ?></div>
    <?php } ?>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
    <?php } ?>

</div>
</main>
<?php include '../includes/footer.php'; ?>

<?php ob_end_flush(); ?>
