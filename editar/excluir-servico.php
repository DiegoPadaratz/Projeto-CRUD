<?php 
    include("../conexao.php");

    $os = intval($_GET['os']);

    $sql_servicos = "SELECT * FROM servicos WHERE os='$os'";
    $query_servicos = $mysqli->query($sql_servicos);
    $servico = $query_servicos->fetch_assoc();

    if(isset($_POST['sim'])){
        $sql_exclusao = "DELETE FROM servicos WHERE os = '$os'";
        $query_exclusao = $mysqli->prepare($sql_exclusao) or die($mysqli->error);
        $query_exclusao->execute();

        if($query_exclusao){
?>
            <h1>Serviço deletado com sucesso!</h1>
            <nav><a href="../consultas/servicos.php">Voltar à lista de consulta</a></nav><hr><br>
<?php 
        die();
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Serviço</title>
</head>
<body>
    <header>
        <h1>Tem certeza que deseja excluir os dados deste serviço?</h1>
    </header>
    <main>
        <form action="" method="post">
            <p><button type="submit" name="sim" value="1">Sim</button></p>
            <p><button><a href="../consultas/servicos.php">Não</a></button></p>
        </form>
    </main>
</body>
</html>