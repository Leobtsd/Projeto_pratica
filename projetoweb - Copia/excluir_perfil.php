<?php 

include_once("model_user.php");
include_once("connection.php");

if(isset($_POST['user_id'])) { 


    $user = new User($connection);

    $user->DeleteUser($_POST['user_id']);


} else {

    $_SESSION['msg_form'] = "Erro no envio, por favor preencha novamente";

    header("Location: profile.php");
    exit();
}




?>