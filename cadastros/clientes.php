<?php 
    include("../conexao.php");

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
                $sql_email = "SELECT * FROM clientes WHERE email=?";
                $query_email = $mysqli->prepare($sql_email) or die($mysqli->error);
                $query_email->bind_param("s", $email);
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
            $sql_telefone = "SELECT * FROM clientes WHERE telefone=?";
            $query_telefone = $mysqli->prepare($sql_telefone) or die($mysqli->error);
            $query_telefone->bind_param("s", $telefone);
            $query_telefone->execute();
            $query_telefone->store_result();

            if($query_telefone->num_rows() > 0){
                $error = "<p class=\"error\">ERRO: Telefone já existente.</p>";
            }
        }

        //Validando cidade
        if(empty($cidade) || is_numeric($cidade)){
            $error = "<p class=\"error\">Preencha a cidade.</p>";
        }else if(strlen($cidade) > 40){
            $error = "<p class=\"error\">O nome da cidade deve conter no máximo 40 caracteres.</p>";
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
                $sql_cpf = "SELECT * FROM clientes WHERE cpf=?";
                $query_cpf = $mysqli->prepare($sql_cpf) or die($mysqli->error);
                $query_cpf->bind_param("s", $cpf);
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
                $sql_cnpj = "SELECT * FROM clientes WHERE cnpj=?";
                $query_cnpj = $mysqli->prepare($sql_cnpj) or die($mysqli->error);
                $query_cnpj->bind_param("s", $cnpj);
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
            //Enviando ao banco de dados
            $sql_code = "INSERT INTO clientes (nome, telefone, email, cidade, endereco, empresa, cpf, cnpj) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $query = $mysqli->prepare($sql_code) or die($mysqli->error);
            $query->bind_param("ssssssss", $nome, $telefone, $email, $cidade, $endereco, $empresa, $cpf, $cnpj);
            $query->execute();

            if($query){
                echo "<p>Cliente cadastrado com sucesso!.</p>";
            }
        }
    }
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
    <title>Cadastro Clientes</title>
</head>
<body>
    <header>
        <h1>Cadastrar novo Cliente</h1>
        <nav><a href="../consultas/consulta.php">Consulta de Dados</a> | <a href="empresas.php">Cadastrar nova empresa</a> | <a href="servicos.php">Cadastrar novo serviço</a></nav><hr><br>
    </header>
    <main>
        <section>
            <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                <p>
                    <label>Nome </label><input type="text" name="nome" value="<?php if(isset($_POST['nome'])){ echo $_POST['nome']; }?>"><span class="error"> *</span>
                </p>
                <p>
                    <label>E-mail </label><input type="email" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; }?>">
                </p>
                <p>
                    <label>Telefone </label><input type="tel" name="telefone" value="<?php if(isset($_POST['telefone'])){ echo $_POST['telefone']; }?>"><span class="error"> *</span>
                </p>
                <p>
                    <label>Cidade </label><input type="text" name="cidade" value="<?php if(isset($_POST['cidade'])){ echo $_POST['cidade']; }?>"><span class="error"> *</span>
                </p>
                <p>
                    <label>Endereço </label><textarea name="endereco"><?php if(isset($_POST['endereco'])){ echo $_POST['endereco']; }?></textarea>
                </p>
                <p>
                    <label>Empresa </label><input type="text" name="empresa" value="<?php if(isset($_POST['empresa'])){ echo $_POST['empresa']; }?>">
                </p>
                <p>
                    <label>CPF </label><input type="text" name="cpf" value="<?php if(isset($_POST['cpf'])){ echo $_POST['cpf']; }?>">
                </p>
                <p>
                    <label>CNPJ </label><input type="text" name="cnpj" value="<?php if(isset($_POST['nome'])){ echo $_POST['cnpj']; }?>">
                </p>
                <p>
                    <button type="submit">Cadastrar</button>
                </p>
            </form>
        </section>
    </main>
</body>
</html>