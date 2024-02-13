<?php
    include_once('config.php');

    $movie_id = $_GET['id'];

    $remove_movie = "UPDATE movies SET status=0 WHERE id = $movie_id";

    if($conn->query($remove_movie)){
        $remove_cast_map = $conn->query("UPDATE movie_cast_mapping SET status=0 WHERE movie_id = $movie_id");

        $remove_category_map = $conn->query("UPDATE movie_category_mapping SET status=0 WHERE movie_id = $movie_id");

        echo json_encode(array("massage"=>"Movie Deleted", "movieId"=>$movie_id));
    }else{
        echo json_encode(array("massage"=>"Fail to Delete", "movieId"=>$movie_id));
    }

?>