<?php

    include_once('config.php');

    if(isset($_POST['category'])){
        $category = $_POST['category'];

        $result = $conn->query("INSERT INTO categories (category) VALUES ('$category')");
    
        if($result){
            echo json_encode(array("message"=>"Category Added","categoryId"=>$conn->insert_id));
        }else{
            echo json_encode(array("message"=>"Failed to add category","categoryId"=>$conn->insert_id,"error"=>$conn->error));
        }
    }elseif($_POST['cast']){
        $cast = $_POST['cast'];

        $result = $conn->query("INSERT INTO cast_and_crew (cast_name) VALUES ('$cast')");
    
        if($result){
            echo json_encode(array("message"=>"Cast Added","castId"=>$conn->insert_id));
        }else{
            echo json_encode(array("message"=>"Failed to add cast","castId"=>$conn->insert_id));
        }
    }
   
    
?>
