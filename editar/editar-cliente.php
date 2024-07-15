<?php 
    include("../conexao.php");

    $id = intval($_GET['id']);

    if(count($_POST) > 0){

        $error = false;

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = limparTelefone($_POST['telefone']);
        $cidade = limparNome($_POST['cidade']);
        $endereco = $_POST['endereco'];
        $empresa = $_POST['empresa'];
        $cpf = limparTelefone($_POST['cpf']);
        $cnpj = limparTelefone($_POST['cnpj']);

        //Validando nome
        if(empty($nome) || is_numeric($nome)){
            $error = "<p class=\"error\">Preencha o nome.</p>";
        }else if(strlen($nome) > 100 || strlen($nome) <= 2){
            $error = "<p class=\"error\">O nome deve conter entre 3-100 caracteres.</p>";
        }

        //Validando email
        if(!empty($email)){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error = "<p class=\"error\">Preencha o e-mail corretamente.</p>";
            }else{
                $sql_email = "SELECT * FROM clientes WHERE email=? AND id <>?";
                $query_email = $mysqli->prepare($sql_email) or die($mysqli->error);
                $query_email->bind_param("si", $email, $id);
                $query_email->execute();
                $query_email->store_result();

                if($query_email->num_rows() > 0){
                    $error = "<p class=\"error\">ERRO: E-mail já existente.</p>";
                }
            }
        }else{
            $email = NULL;
        }

        //Validando telefone
        if(empty($telefone)){
            $error = "<p class=\"error\">Preencha o telefone.</p>";
        }else if(strlen($telefone) != 11 || !is_numeric($telefone) || preg_match('/^(\d)\1*$/', $telefone)){
            $error = "<p class=\"error\">Preencha o telefone corretamente.</p>";
        }else{
            $sql_telefone = "SELECT * FROM clientes WHERE telefone=? AND id <>?";
            $query_telefone = $mysqli->prepare($sql_telefone) or die($mysqli->error);
            $query_telefone->bind_param("si", $telefone, $id);
            $query_telefone->execute();
            $query_telefone->store_result();

            if($query_telefone->num_rows() > 0){
                $error = "<p class=\"error\">ERRO: Telefone já existente.</p>";
            }
        }

        //Validando cidade
        if(empty($cidade)){
            $error = "<p class=\"error\">Preencha a cidade.</p>";
        }else if(strlen($cidade) > 40 || is_numeric($cidade)){
            $error = "<p class=\"error\">Preencha a cidade corretamente.</p>";
        }

        //Validando endereço
        if(!empty($endereco)){
            if(strlen($endereco) > 150 || strlen($endereco) < 30 || is_numeric($endereco)){
                $error = "<p class=\"error\">Preencha o endereço corretamente.</p>";
            }
        }

        //Validando empresa
        if(!empty($empresa)){
            if(strlen($empresa) > 40 || is_numeric($empresa)){
                $error = "<p class=\"error\">Preencha o nome da empresa corretamente.</p>";
            }
        }

        //Validando CPF
        if(!empty($cpf)){
            if(strlen($cpf) != 11 || preg_match('/^(\d)\1*$/', $cpf) || !is_numeric($cpf)){
                $error = "<p class=\"error\">Preencha o CPF corretamente</p>";
            }else{
                $sql_cpf = "SELECT * FROM clientes WHERE cpf=? AND id <>?";
                $query_cpf = $mysqli->prepare($sql_cpf) or die($mysqli->error);
                $query_cpf->bind_param("si", $cpf, $id);
                $query_cpf->execute();
                $query_cpf->store_result();

                if($query_cpf->num_rows() > 0){
                    $error = "<p class=\"error\">ERRO: CPF já existente.</p>";
                }
            }
        }else{
            $cpf = NULL;
        }

        //Validando CNPJ
        if(!empty($cnpj)){
            if(strlen($cnpj) != 14 || preg_match('/^(\d)\1*$/', $cnpj) || !is_numeric($cnpj)){
                $error = "<p class=\"error\">Preencha o CNPJ corretamente</p>";
            }else{
                $sql_cnpj = "SELECT * FROM clientes WHERE cnpj=? AND id <>?";
                $query_cnpj = $mysqli->prepare($sql_cnpj) or die($mysqli->error);
                $query_cnpj->bind_param("si", $cnpj, $id);
                $query_cnpj->execute();
                $query_cnpj->store_result();

                if($query_cnpj->num_rows() > 0){
                    $error = "<p class=\"error\">ERRO: CNPJ já existente.</p>";
                }
            }
        }else{
            $cnpj = NULL;
        }

        //Verificando CPF e CNPJ
        if(empty($cpf) && empty($cnpj)){
            $error = "<p class=\"error\">Preencha o CPF ou o CNPJ</p>";
        }

        //Se ocorrer algum erro, mostrar
        if($error){
            echo "<p>$error</p>";
        }else{
            $sql_update = "UPDATE clientes SET nome = ?, email = ?, telefone = ?, cidade = ?, endereco = ?, empresa = ?, cpf = ?, cnpj = ? WHERE id = '$id'";
            $query_update = $mysqli->prepare($sql_update) or die($mysqli->error);
            $query_update->bind_param("ssssssss", $nome, $email, $telefone, $cidade, $endereco, $empresa, $cpf, $cnpj);
            $query_update->execute();

            if($query_update){
                echo "<p>Cliente atualizado com sucesso!</p>";
                unset($_POST);
            }
        }
    }

    $sql_cliente = "SELECT * FROM clientes WHERE id='$id'";
    $query_cliente = $mysqli->query($sql_cliente);
    $cliente = $query_cliente->fetch_assoc();
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
    <title>Atualizar Cadastro Cliente</title>
</head>
<body>
    <header>
        <h1>Atualize os Dados Cadastrados de <?=$cliente['nome'];?></h1>
        <nav><a href="../consultas/clientes.php">Voltar</a></nav><hr><br>
    </header>
    <main>
        <section>
            <form action="" method="post">
                <p>
                    <label>Nome </label><input type="text" name="nome" value="<?php if(isset($_POST['nome'])){ echo $_POST['nome']; }else{ echo $cliente['nome']; } ?>"><span class="error"> *</span>
                </p>
                <p>
                    <label>E-mail </label><input type="email" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; }else{ echo $cliente['email']; } ?>">
                </p>
                <p>
                    <label>Telefone </label><input type="tel" name="telefone" value="<?php if(isset($_POST['telefone'])){ echo $_POST['telefone']; }else{ echo $cliente['telefone']; } ?>"><span class="error"> *</span>
                </p>
                <p>
                    <label>Cidade </label><input type="text" name="cidade" value="<?php if(isset($_POST['cidade'])){ echo $_POST['cidade']; }else{ echo $cliente['cidade']; } ?>"><span class="error"> *</span>
                </p>
                <p>
                    <label>Endereço </label><textarea name="endereco"><?php if(isset($_POST['endereco'])){ echo $_POST['endereco']; }else{ echo $cliente['endereco']; } ?></textarea>
                </p>
                <p>
                    <label>Empresa </label><input type="text" name="empresa" value="<?php if(isset($_POST['empresa'])){ echo $_POST['empresa']; }else{ echo $cliente['empresa']; } ?>">
                </p>
                <p>
                    <label>CPF </label><input type="text" name="cpf" value="<?php if(isset($_POST['cpf'])){ echo $_POST['cpf']; }else{ echo $cliente['cpf']; } ?>">
                </p>
                <p>
                    <label>CNPJ </label><input type="text" name="cnpj" value="<?php if(isset($_POST['cnpj'])){ echo $_POST['cnpj']; }else{ echo $cliente['cnpj']; } ?>">
                </p>
                <p>
                    <button type="submit">Atualizar Cadastro</button>
                </p>
            </form>
        </section>
    </main>
</body>
</html>