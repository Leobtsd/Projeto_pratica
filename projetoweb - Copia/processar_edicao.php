<?php 

include_once("model_user.php");
include_once("connection.php");





if (!empty($_POST['update_user'])) { // verifica se o formulário não está vazio

    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT); // transforma o formulário recebido em um array assossiativo

    $user = new User($connection);

    $user->UpdateUser($dados['novo_nome'], $dados['senha'], $dados['nova_senha'], $dados['novo_periodo'], $dados['id']);

        

} else {

    $_SESSION['msg_form'] = "Erro no envio do formuário, por favor preencha novamente";

    header("Location: profile.php");
    exit();
}

