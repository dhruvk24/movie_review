<?php
    include_once("config.php");

    $movie_id = isset($_POST["id"]) ? $_POST["id"] : "";
    $movie_name = $_POST['movieName'];
    $categories = $_POST['category'];
    $cast_crew  = $_POST['castAndCrew'];
    $rating = $_POST['rating'];


    $modify_movie = "UPDATE movies SET movie_name='$movie_name', rating=$rating WHERE id=$movie_id";

    if($conn->query($modify_movie)){

        $categories_arr = [];
        foreach($categories as $category){
                // we can use not in operator in query
            $has_category = $conn->query("SELECT mcg.id FROM movies as m LEFT JOIN movie_category_mapping as mcg ON m.id=mcg.movie_id LEFT JOIN categories AS c ON mcg.category_id=c.id WHERE m.id=$movie_id AND c.category='$category'");
    
            if($has_category->num_rows > 0){
                $category_map_id = $has_category->fetch_assoc()['id'];
                $conn->query("UPDATE movie_category_mapping SET status=1 WHERE id=$category_map_id");
                array_push($categories_arr,$category_map_id);
            }else{
                
                $get_category = $conn->query("SELECT id FROM categories WHERE category='$category'");
                if($get_category->num_rows > 0 ){
    
                    $category_id = $get_category->fetch_assoc()['id']; 
                    $add_new_category = $conn->query("INSERT INTO movie_category_mapping (movie_id, category_id) VALUES ($movie_id,$category_id)");
    
                    array_push($categories_arr,$conn->insert_id);
                }
            }
           
        }
        $categories_str = implode(', ',$categories_arr);
        $remove_old_category = $conn->query("UPDATE movies AS m LEFT JOIN movie_category_mapping AS mcg ON m.id=mcg.movie_id SET mcg.status=0 WHERE m.id=$movie_id AND  mcg.id NOT IN ($categories_str)");
     

        $cast_arr = []; 
        foreach($cast_crew as $cast){
                
            $has_cast = $conn->query("SELECT mct.id FROM movies as m LEFT JOIN movie_cast_mapping as mct ON m.id=mct.movie_id LEFT JOIN cast_and_crew AS ct ON mct.cast_id=ct.id WHERE m.id=$movie_id AND ct.cast_name='$cast'");
    
            if($has_cast->num_rows > 0){
                $cast_map_id = $has_cast->fetch_assoc()['id'];
                $conn->query("UPDATE movie_cast_mapping SET status=1 WHERE id=$cast_map_id");
                array_push($cast_arr,$cast_map_id);
            }else{
    
                $get_cast = $conn->query("SELECT id FROM cast_and_crew WHERE cast_name='$cast'");
                if($get_cast->num_rows > 0 ){
    
                    $cast_id = $get_cast->fetch_assoc()['id']; 
                    $add_new_category = $conn->query("INSERT INTO movie_cast_mapping (movie_id, cast_id) VALUES ($movie_id,$cast_id)");
    
                    array_push($cast_arr,$conn->insert_id);
                }
            }
        }
    
        $cast_str = implode(', ',$cast_arr);
        $remove_old_cast = $conn->query("UPDATE movies AS m LEFT JOIN movie_cast_mapping AS mct ON m.id=mct.movie_id SET mct.status=0 WHERE m.id=$movie_id AND  mct.id NOT IN ($cast_str)");   
        
        echo json_encode(array("massage"=>"Movie Updated", "movieId"=>$movie_id));
    }else{
        echo json_encode(array("massage"=>"Fail to Update", "movieId"=>$movie_id));
    }

?>