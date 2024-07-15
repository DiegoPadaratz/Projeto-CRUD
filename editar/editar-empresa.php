<?php 
    include("../conexao.php");

    $id = intval($_GET['id']);

    if(count($_POST) > 0){

        $error = false;

        $nome = $_POST['nome'];
        $cidade = $_POST['cidade'];
        $endereco = $_POST['endereco'];
        $proprietario = $_POST['proprietario'];
        $cnpj = limparTelefone($_POST['cnpj']);

        //Validando nome
        if(empty($nome) || is_numeric($nome)){
            $error = "<p class=\"error\">Preencha o nome da empresa</p>";
        }else if(strlen($nome) > 40){
            $error = "<p class=\"error\">O nome da empresa deve conter no máximo 40 caracteres.</p>";
        }

        //Validando cidade
        if(empty($cidade) || is_numeric($cidade)){
            $error = "<p class=\"error\">Preencha a cidade</p>";
        }else if(strlen($cidade) > 40){
            $error = "<p class=\"error\">O nome da cidade deve conter no máximo 40 caracteres.</p>";
        }

        //Validando endereço
        if(!empty($endereco)){
            if(strlen($endereco) > 150 || strlen($endereco) < 30 || is_numeric($endereco)){
                $error = "<p class=\"error\">Preencha o endereço corretamente.</p>";
            }
        }

        //Validando proprietário
        if(!empty($proprietario)){
            if(strlen($proprietario) > 100 || strlen($proprietario) <= 2 || is_numeric($proprietario)){
                $error = "<p class=\"error\">O nome do proprietário deve conter entre 3-100 caracteres.</p>";
            }
        }

        //Validando CNPJ
        if(empty($cnpj)){
            $error = "<p class=\"error\">Preencha o CNPJ.</p>";
        }else if(strlen($cnpj) != 14 || !is_numeric($cnpj)){
            $error = "<p class=\"error\">Preencha o CNPJ corretamente.</p>";
        }else{
            $sql_cnpj = "SELECT * FROM empresas WHERE cnpj=? AND id <>?";
            $query_cnpj = $mysqli->prepare($sql_cnpj) or die($mysqli->error);
            $query_cnpj->bind_param("si", $cnpj, $id);
            $query_cnpj->execute();
            $query_cnpj->store_result();

            if($query_cnpj->num_rows() > 0){
                $error = "<p class=\"error\">ERRO: CNPJ já existente.</p>";
            }
        }

        //Se ocorrer algum erro, mostrar
        if($error){
            echo "<p>$error</p>";
        }else{
            $sql_code = "UPDATE empresas SET nome = ?, cidade = ?, endereco = ?, proprietario = ?, cnpj = ? WHERE id = '$id'";
            $query = $mysqli->prepare($sql_code) or die($mysqli->error);
            $query->bind_param("sssss", $nome, $cidade, $endereco, $proprietario, $cnpj);
            $query->execute();

            if($query){
                echo "Empresa atualizada com sucesso!";
                unset($_POST);
            }
        }
    }

    $sql_empresa = "SELECT * FROM empresas WHERE id='$id'";
    $query_empresa = $mysqli->query($sql_empresa) or die($mysqli->error);
    $empresa = $query_empresa->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .error{
            color: red;
        }
    </style>
    <title>Cadastro Empresas</title>
</head>
<body>
    <header>
        <h1>Atualize os dados cadastrados de <?=$empresa['nome']; ?></h1>
        <nav><a href="../consultas/empresas.php">Voltar</a></nav><hr><br>
    </header>
    <main>
        <section>
            <form action="" method="post">
                <p>
                    <label>Nome </label><input type="text" name="nome" value="<?=$empresa['nome']; ?>"><span class="error"> *</span>
                </p>
                <p>
                    <label>Cidade </label><input type="text" name="cidade" value="<?php if(isset($_POST['cidade'])){ echo $_POST['cidade']; }else{ echo $empresa['cidade']; }?>"><span class="error"> *</span>
                </p>
                <p>
                    <label>Endereço </label><textarea name="endereco"><?php if(isset($_POST['endereco'])){ echo $_POST['endereco']; }else{ echo $empresa['endereco']; }?></textarea>
                </p>
                <p>
                    <label>Proprietário </label><input type="text" name="proprietario" value="<?php if(isset($_POST['proprietario'])){ echo $_POST['proprietario']; }else{ echo $empresa['proprietario']; }?>">
                </p>
                <p>
                    <label>CNPJ </label><input type="text" name="cnpj" value="<?php if(isset($_POST['cnpj'])){ echo $_POST['cnpj']; }else{ echo $empresa['cnpj']; }?>"><span class="error"> *</span>
                </p>
                <p>
                    <button type="submit">Atualizar Cadastro</button>
                </p>
            </form>
        </section>
    </main>
</body>
</html>