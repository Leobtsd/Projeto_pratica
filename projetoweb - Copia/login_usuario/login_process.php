<?php 

include_once("../model_user.php");
include_once("../connection.php");





if (!empty($_POST['SendLogin'])) { // verifica se o formulário não está vazio

    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT); // filtra os dados e transforma o formulário recebido em um array assossiativo


    $user = new User($connection);

    $user->LoginUser($dados['use_email'], $dados['use_password']);

        

} else {

    $_SESSION['msg_form'] = "Erro no envio do formuário, por favor preencha novamente";

    header("Location: login.php");
    exit();
}




?>