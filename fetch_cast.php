<?php
    include_once('config.php');
    
    $sql = $conn->query("SELECT * from cast_and_crew WHERE status=1");
    if($sql){
        $result = $sql->fetch_all(MYSQLI_ASSOC);
        echo json_encode($result);
    }else{
        echo json_encode(array($conn->error));
    }

?>