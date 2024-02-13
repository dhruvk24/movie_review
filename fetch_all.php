<?php

    // header('Content-Type: apllication/json');
    include_once('config.php');
    if(isset($_GET['id'])){
        $movie_id = $_GET['id'];
        $sql = "SELECT m.id,m.movie_name,m.rating, GROUP_CONCAT(DISTINCT c.category) AS categories, GROUP_CONCAT(DISTINCT ct.cast_name) AS cast_crew FROM movies AS m LEFT JOIN movie_category_mapping as mcg ON m.id=mcg.movie_id AND mcg.status=1 LEFT JOIN categories AS c ON mcg.category_id=c.id AND c.status=1 LEFT JOIN movie_cast_mapping AS mct ON m.id=mct.movie_id AND mct.status=1 LEFT JOIN cast_and_crew AS ct ON mct.cast_id=ct.id AND ct.status=1 WHERE m.status=1 GROUP BY m.id HAVING m.id=$movie_id ORDER BY m.id DESC";

        $result = $conn->query($sql);
        if($result->num_rows>0){
            echo json_encode(array($result->fetch_assoc()));
        }
    }else{
        $sql = "SELECT m.id,m.movie_name,m.rating, GROUP_CONCAT(DISTINCT c.category) AS categories, GROUP_CONCAT(DISTINCT ct.cast_name) AS cast_crew FROM movies AS m LEFT JOIN movie_category_mapping as mcg ON m.id=mcg.movie_id AND mcg.status=1 LEFT JOIN categories AS c ON mcg.category_id=c.id AND c.status=1 LEFT JOIN movie_cast_mapping AS mct ON m.id=mct.movie_id AND mct.status=1 LEFT JOIN cast_and_crew AS ct ON mct.cast_id=ct.id AND ct.status=1 WHERE m.status=1 GROUP BY m.id ORDER BY m.id DESC";
        $result = $conn->query($sql);
        if($result->num_rows>0){
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            // header('location: index.html');
        }
    }
?>
