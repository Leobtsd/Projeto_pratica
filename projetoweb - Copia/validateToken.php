<?php 

function validationToken(){


    if (isset($_COOKIE['token'])) {

    $token = $_COOKIE['token'];
    } else {
        $_SESSION['msg_validation'] = "Error autenticação, por favor faça o login novamente";
        header('Location: index.php');
        exit();
    }

    $token_array = explode('.', $token);
    $header = $token_array[0];
    $payload = $token_array[1];
    $signature = $token_array[2];

    $key = "HDHFDKFHDKFJOO56KC";

    $validar_assinatura = hash_hmac('sha256', "$header.$payload", $key, true);

    $validar_assinatura = base64_encode($validar_assinatura);


    if ($signature == $validar_assinatura){

        $dadosToken = base64_decode($payload);

        $dadosToken = json_decode($dadosToken);

        if ($dadosToken->exp > time()) {

            return true;
        } else {

            return false;
        }
        

    } else {
        return false;
    }



}




?>