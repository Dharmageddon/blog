<?php include "../lock.php"; ?>

<?php

$title = $slug = $image = $body = "";
$title_err = $slug_err = $image_err = $body_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    $input_title = trim($_POST["title"]);
    if(empty($input_title)){
        $title_err = "Please enter an title.";
    } else {
        $title = $input_title;
    }

    $input_slug = trim($_POST["slug"]);
    if(empty($input_slug)){
        $slug_err = "Please enter an slug.";
    } else {
        $slug = $input_slug;
    }

    $input_image = trim($_POST["image"]);
    if(empty($input_image)){
        $image_err = "Please enter an image.";
    } else {
        $image = $input_image;
    }

    $input_body = trim($_POST["body"]);
    if(empty($input_body)){
        $body_err = "Please enter an body.";
    } else {
        $body = $input_body;
    }

    // Check input errors before inserting in database
    if(empty($title_err) && empty($slug_err) && empty($image_err) && empty($body_err)){
        // Prepare an update statement
        $sql = "UPDATE posts SET title=?, slug=?, image=?, body=? WHERE id=?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssi", $param_title, $param_slug, $param_image, $param_body, $param_id);

            // Set parameters
            $param_title = $title;
            $param_slug = $slug;
            $param_image = $image;
            $param_body = $body;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM posts WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();

                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $title = $row["title"];
                    $slug = $row["slug"];
                    $image = $row["image"];
                    $body = $row["body"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $mysqli->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<?php include '../includes/header.php'; ?>

<main role="main" class="flex-shrink-0">
<div class="container">

    <form class="container p-3" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
      <fieldset>
        <legend class="pt-2"><i class="fas fa-edit"></i> Update Post</legend>
        <hr>
        <div class="form-group row <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">Title</label>
            <input type="text" name="title" class="form-control col-sm-8" placeholder="Title" value="<?php echo $title; ?>">
            <div class="text-danger offset-sm-2"><?php echo $title_err;?></div>
        </div>
        <div class="form-group row <?php echo (!empty($slug_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">Slug</label>
            <input type="text" name="slug" class="form-control col-sm-8" placeholder="Slug" value="<?php echo $slug; ?>" readonly>
            <span class="text-danger offset-sm-2"><?php echo $slug_err;?></span>
        </div>
        <div class="form-group row <?php echo (!empty($image_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">Image</label>
            <input type="text" name="image" class="form-control col-sm-8" placeholder="Image" value="<?php echo $image; ?>">
            <span class="text-danger offset-sm-2"><?php echo $image_err;?></span>
        </div>
        <div class="form-group row <?php echo (!empty($body_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">Body</label>
            <textarea name="body" class="form-control col-sm-8" rows="10" placeholder="Body"><?php echo $body; ?></textarea>
            <span class="text-danger offset-sm-2"><?php echo $body_err;?></span>
        </div>
        <div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="submit" class="btn btn-primary" value="Update">
            <a href="index.php" class="btn btn-link">Cancel</a>
        </div>
      </fieldset>
    </form>

</div>
</main>

<?php include '../includes/footer.php'; ?>
