<?php 
    $host = "127.0.0.1";
    $user = "root";
    $pass = "";
    $bd = "dtech";

    $mysqli = new mysqli($host, $user, $pass, $bd);

    if($mysqli->connect_error){
        die($mysqli->connect_error);
    }

    function limparNome($nome) {
        // Remove caracteres que não são letras ou espaços
        $nomeLimpo = preg_replace('/[^a-zA-Z\s]/', '', $nome);
    
        // Remove espaços extras
        $nomeLimpo = preg_replace('/\s+/', ' ', $nomeLimpo);
    
        // Remove espaços no início e no final
        $nomeLimpo = trim($nomeLimpo);
    
        return $nomeLimpo;
    }

    //Função limpar telefone
    function limparTelefone($telefone){
        // Remove espaços, parênteses, traços e outros caracteres não numéricos
        return preg_replace('/[^0-9]/', '', $telefone);
    }

    //Formata telefone
    function formataTelefone($telefone){
        $ddd = substr($telefone, 0, 2);
        $parte1 = substr($telefone, 2, 5);
        $parte2 = substr($telefone, 7);

        return "($ddd) $parte1-$parte2";
    }

    //Formata CPF
    function formataCpf($cpf){
        $parte1 = substr($cpf, 0, 3);
        $parte2 = substr($cpf, 3, 3);
        $parte3 = substr($cpf, 6, 3);
        $parte4 = substr($cpf, 9, 2);

        return "$parte1.$parte2.$parte3-$parte4";
    }

    //Formata CNPJ
    function formataCnpj($cnpj){
        $parte1 = substr($cnpj, 0, 2);
        $parte2 = substr($cnpj, 2, 3);
        $parte3 = substr($cnpj, 5, 3);
        $parte4 = substr($cnpj, 8, 4);
        $parte5 = substr($cnpj, 12);

        return "$parte1.$parte2.$parte3/$parte4-$parte5";
    }

    //Formata preço
    function formataPreco($preco){
        $padrao = numfmt_create("pt_BR", NumberFormatter::CURRENCY);
        return numfmt_format_currency($padrao, $preco, "BRL");
    }

    //Formata data
    function formataData($data){
        return date("d/m/Y | H:i", strtotime($data));
    }

    //Data atual
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtual = date("Y-m-d H:i:s", time());
?>