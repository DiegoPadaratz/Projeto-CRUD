<?php 
    include("../conexao.php");

    if(count($_POST) > 0){

        $error = false;

        $os = $_POST['os'];
        $tipo = $_POST['tipo'];
        $maquina = $_POST['maquina'];
        $fabricante = $_POST['fabricante'];
        $modelo = $_POST['modelo'];
        $servico = $_POST['servico'];
        $comentario = $_POST['comentario'];
        $preco = $_POST['preco'];
        $cliente = $_POST['cliente'];
        $empresa = $_POST['empresa'];
        $cidade = $_POST['cidade'];

        //Validando OS
        if(empty($os)){
            $error = "<p class=\"error\">Preencha a OS.</p>";
        }else if(!is_numeric($os) || $os != (int)$os){
            $error = "<p class=\"error\">Preencha a OS corretamente.</p>";
        }else{
            $sql_os = "SELECT * FROM servicos WHERE os=?";
            $query_os = $mysqli->prepare($sql_os) or die($mysqli->error);
            $query_os->bind_param("s", $os);
            $query_os->execute();
            $query_os->store_result();

            if($query_os->num_rows() > 0){
                $error = "<p class=\"error\">ERRO: Número da OS já existente.</p>";
            }
        }

        //Validando tipo
        if(empty($tipo)){
            $error = "<p class=\"error\">Preencha o tipo do serviço.</p>";
        }else if($tipo != "Remoto" && $tipo != "Chamado" && $tipo != "Laboratório"){
            $error = "<p class=\"error\">Preencha um tipo de serviço válido.</p>";
        }

        //Validando máquina
        if(!empty($maquina)){
            if(strlen($maquina) > 20 || strlen($maquina) < 2 || is_numeric($maquina)){
                $error = "<p class=\"error\">O nome da máquina deve conter entre 2-20 caracteres.</p>";
            }
        }

        //Validando fabricante
        if(!empty($fabricante)){
            if($fabricante != "DELL" && $fabricante != "HP" && $fabricante != "ASUS" && $fabricante != "ACER" && $fabricante != "VAIO" && $fabricante != "POSITIVO" && $fabricante != "LENOVO" && $fabricante != "SAMSUNG" && $fabricante != "APPLE"){
                $error = "<p class=\"error\">Preencha um fabricante válido.</p>";
            }
        }

        //Validando modelo
        if(!empty($modelo)){
            if(strlen($modelo) > 20 || strlen($modelo) < 5 || is_numeric($modelo)){
                $error = "<p class=\"error\">O modelo da máquina deve conter entre 5-20 caracteres.</p>";
            }
        }

        //Validando serviço
        if(empty($servico)){
            $error = "<p class=\"error\">Preencha o serviço que foi prestado.</p>";
        }else if(strlen($servico) > 150 || strlen($servico) < 5){
            $error = "<p class=\"error\">O serviço prestado deve conter entre 5-150 caracteres.</p>";
        }

        //Validando comentário
        if(!empty($comentario)){
            if(strlen($comentario) > 500 || strlen($comentario) < 10 || is_numeric($comentario)){
                $error = "<p class=\"error\">O comentário deve conter entre 10-500 caracteres.</p>";
            }
        }

        //Validando preço
        if(empty($preco)){
            $error = "<p class=\"error\">Preencha o preço do serviço.</p>";
        }else if(!is_numeric($preco) || strlen($preco) > 10){
            $error = "<p class=\"error\">Preencha o preço do serviço corretamente.</p>";
        }

        //Validando cliente
        if(!empty($cliente)){
            if(strlen($cliente) > 100 || strlen($cliente) <= 2 || is_numeric($cliente)){
                $error = "<p class=\"error\">O nome do cliente deve conter entre 3-100 caracteres.</p>";
            }
        }
        
        //Validando empresa
        if(!empty($empresa)){
            if(strlen($empresa) > 40 || is_numeric($empresa)){
                $error = "<p class=\"error\">Preencha o nome da empresa corretamente.</p>";
            }
        }

        //Validando cidade
        if(empty($cidade) || is_numeric($cidade)){
            $error = "<p class=\"error\">Preencha a cidade em que foi realizado o serviço</p>";
        }else if(strlen($cidade) > 40){
            $error = "<p class=\"error\">O nome da cidade deve conter no máximo 40 caracteres.</p>";
        }

        //Se cliente e empresa forem vazios
        if(empty($empresa) && empty($cliente)){
            $error = "<p class=\"error\">Preencha o nome do cliente ou o nome da empresa.</p>";
        }

        //Se houver fabricante e modelo for vazio
        if(!empty($fabricante) && empty($modelo)){
            $error = "<p class=\"error\">Preencha o modelo do fabricante informado.</p>";
        }

        //Se houver modelo e fabricante for vazio
        if(!empty($modelo) && empty($fabricante)){
            $error = "<p class=\"error\">Preencha o fabricante do modelo informado.</p>";
        }

        //Se houver fabricante e modelo e não houver máquina
        if(!empty($fabricante) && !empty($modelo) && empty($maquina)){
            $error = "<p class=\"error\">Preencha a máquina para validar o fabricante e o modelo informado.</p>";
        }

        //Se ocorrer algum erro, mostrar
        if($error){
            echo "<p>$error</p>";
        }else{
            //Enviando ao banco de dados
            $sql_code = "INSERT INTO servicos (os, tipo, maquina, fabricante, modelo, servico, comentario, preco, cliente, empresa, cidade, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $query = $mysqli->prepare($sql_code) or die($mysqli->error);
            $query->bind_param("ssssssssssss", $os, $tipo, $maquina, $fabricante, $modelo, $servico, $comentario, $preco, $cliente, $empresa, $cidade, $dataAtual);
            $query->execute();

            if($query){
                echo "<p>Serviço cadastrado com sucesso!.</p>";
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
    <title>Cadastro Serviços</title>
</head>
<body>
    <header>
        <h1>Cadastrar novo Serviço</h1>
        <nav><a href="../consultas/consulta.php">Consulta de Dados</a> | <a href="clientes.php">Cadastrar novo cliente</a> | <a href="empresas.php">Cadastrar nova empresa</a></nav><hr><br>
    </header>
    <main>
        <section>
            <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                <p>
                    <label>OS </label><input type="number" name="os">
                </p>
                <p>
                    <label>Tipo: </label><span class="error"> *</span>
                    <input type="radio" name="tipo" value="Remoto"> Remoto
                    <input type="radio" name="tipo" value="Chamado"> Chamado
                    <input type="radio" name="tipo" value="Laboratório"> Laboratório
                </p>
                <p>
                    <label>Máquina </label><input type="text" name="maquina">
                </p>
                <p>
                    <label>Fabricante </label>
                    <select name="fabricante">
                        <option value="">Selecione o fabricante</option>
                        <option value="DELL">DELL</option>
                        <option value="HP">HP</option>
                        <option value="ASUS">ASUS</option>
                        <option value="ACER">ACER</option>
                        <option value="VAIO">VAIO</option>
                        <option value="POSITIVO">POSITIVO</option>
                        <option value="LENOVO">LENOVO</option>
                        <option value="SAMSUNG">SAMSUNG</option>
                        <option value="APPLE">APPLE</option>
                    </select>
                </p>
                <p>
                    <label>Modelo </label><input type="text" name="modelo">
                </p>
                <p>
                    <label>Serviço prestado </label><textarea name="servico" cols="30" rows="10"></textarea></label><span class="error"> *</span>
                </p>
                <p>
                    <label>Comentário </label><textarea name="comentario"></textarea>
                </p>
                <p>
                    <label>Preço </label><input type="number" name="preco" step="0.01"></label><span class="error"> *</span>
                </p>
                <p>
                    <label>Cliente </label><input type="text" name="cliente">
                </p>
                <p>
                    <label>Empresa </label><input type="text" name="empresa">
                </p>
                <p>
                    <label>Cidade </label><input type="text" name="cidade"></label><span class="error"> *</span>
                </p>
                <p>
                    <button type="submit">Cadastrar</button>
                </p>
            </form>
        </section>
    </main>
</body>
</html>