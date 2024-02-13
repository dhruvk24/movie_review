<?php
include_once("config.php");

$movie_name = $_POST['movieName'];
$categories = $_POST['category'];
$cast_crew  = $_POST['castAndCrew'];
$rating = $_POST['rating'];



$add_movie = "INSERT INTO movies (movie_name, rating) VALUES ('$movie_name', $rating)";

if($conn->query($add_movie)){
    $movie_id = $conn->insert_id;

    foreach($categories as $category){
        $get_category = $conn->query("SELECT id FROM categories WHERE category LIKE '$category'");

        if($get_category->num_rows > 0 ){
            $category_id = $get_category->fetch_assoc()['id']; 
        }
        else{
            $add_category = "INSERT INTO categories (category) VALUES ('$category')";

            if($conn->query($add_category)){
                $category_id = $conn->insert_id;
            }
        }

        $movie_category_map = "INSERT INTO movie_category_mapping (movie_id, category_id) VALUES ($movie_id,$category_id)";
        $conn->query($movie_category_map);
    }

    foreach($cast_crew as $cast){
        $get_cast = $conn->query("SELECT id FROM cast_and_crew WHERE cast_name LIKE '$cast'");

        if($get_cast->num_rows > 0 ){
            $cast_id = $get_cast->fetch_assoc()['id']; 
        }else{
            $add_cast = "INSERT INTO cast_and_crew (cast_name) VALUES ('$cast')";

            if($conn->query($add_cast)){
                $cast_id = $conn->insert_id;
            }
        }

        $movie_cast_map = "INSERT INTO movie_cast_mapping (movie_id, cast_id) VALUES ($movie_id,$cast_id)";
        $conn->query($movie_cast_map);
    }


    echo json_encode(array("massage"=>"Movie Added", "movieId"=>$movie_id));
}else{
    echo json_encode(array("massage"=>"Fail to add", "movieId"=>null));
}
