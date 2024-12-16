<?php
include_once "db.php";
if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    $sql = $conn->query("SELECT * FROM tb_user WHERE username = '$user'");
    $rw = $sql->fetch();
    if(isset($rw['username'])){
        if($pass == $rw['password']){
            $_SESSION['id'] = $rw['id'];
            header("location: ../frontend/home.php");
        }
}
}
?>