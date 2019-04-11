<?php include "../lock.php"; ?>

<?php include '../includes/header.php'; ?>

<main role="main" class="flex-shrink-0">
<div class="container">

    <?php
    //Pagination Begin
    $sql = "SELECT * FROM posts";

    if ($stmt = $mysqli->query($sql)) {

        /* determine number of rows result set */
        $total = $stmt->num_rows;

        /* close result set */
        $stmt->close();
    }

    // How many items to list per page
    $limit = 4;

    // How many pages will there be
    $pages = ceil($total / $limit);

    // What page are we currently on?
    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
        ),
    )));

    // Calculate the offset for the query
    $offset = ($page - 1) * $limit;

    // The "back" link
    $prevlink = ($page > 1) ? '<li class="page-item"><a class="page-link" href="?page=1">First</a></li>
    <li class="page-item"><a class="page-link" href="?page='.($page - 1).'"><</a></li>'
    : '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">First</a>
    </li><li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"><</a></li>';

    // The "forward" link
    $nextlink = ($page < $pages) ? '<li class="page-item"><a class="page-link" href="?page='.($page + 1).'">></a></li>
    <li class="page-item"><a class="page-link" href="?page='.$pages.'">Last</a></li>'
    : '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">></a></li>
    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Last</a></li>';

    $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT ? OFFSET ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ii', $limit, $offset);

    if($stmt->execute()){
        if($result = $stmt->get_result()){

        echo "<div class='row'>";
        while($row = $result->fetch_object()){
          echo "<div class='col-md-6'>";
          //echo "<h2><a href='post.php?id=".$row->id."'>".$row->title."</a></h2>";
          echo "<h2><a href='post.php?slug=".$row->slug."'>".$row->title."</a></h2>";
          echo "<img class='img-fluid' src='images/".$row->image."'><br>";
          list($excerpt) = explode('<!--more-->', $row->body);
          echo "<div class='pt-3 pb-3'>".$excerpt."</div>";
          echo "</div>";
        }
        echo "</div>";

            echo "<nav><ul class='pagination'>";
            echo $prevlink;
            for ($i = 1; $i <= $pages; $i++) {
                echo "<li class='page-item ";
                if ($page == $i) { echo "active"; }
                echo "'><a class='page-link' href='?page=".$i."'>".$i."</a></li>";
            }
            echo $nextlink;
            echo "</ul></nav>";

            // Free result set
            $result->free();
        } else {
            echo "<p class='lead'><em>No posts were found.</em></p>";
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }

    // Close connection
    $mysqli->close();
    ?>

</div>
</main>

<?php include '../includes/footer.php'; ?>
