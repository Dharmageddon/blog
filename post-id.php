<?php include "../lock.php"; ?>

  <?php
  // Check existence of id parameter before processing further
  if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
      // Prepare a select statement

      $sql = "SELECT * FROM posts WHERE id = ?";

      if($stmt = $mysqli->prepare($sql)){
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("i", $param_id);

          // Set parameters
          $param_id = trim($_GET["id"]);

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
                  // URL doesn't contain valid id parameter. Redirect to error page
                  //header("location: error.php");
                  //exit();
              }

          } else {
              echo "Oops! Something went wrong. Please try again later.";
          }
      }

      // Close statement
      $stmt->close();

      // Close connection
      $mysqli->close();
  } else{
      // URL doesn't contain id parameter. Redirect to error page
      //header("location: error.php");
      //exit();
  }
  ?>

<?php include '../includes/header.php'; ?>

<main role="main" class="flex-shrink-0">
<div class="container">

    <h2><?php echo $title ?></h2>
    <img class='img-fluid' src='images/<?php echo $image; ?>'>
    <div class='pt-3 pb-3'><?php echo $body ?></div>
    <a href="blog.php" class="btn btn-outline-primary">Back</a>

</div>
</main>

<?php include '../includes/footer.php'; ?>
