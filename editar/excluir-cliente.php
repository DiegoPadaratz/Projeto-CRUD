<?php 
    include("../conexao.php");

    $id = intval($_GET['id']);

    $sql_cliente = "SELECT * FROM clientes WHERE id='$id'";
    $query_cliente = $mysqli->query($sql_cliente);
    $cliente = $query_cliente->fetch_assoc();

    if(isset($_POST['sim'])){
        $sql_exclusao = "DELETE FROM clientes WHERE id = '$id'";
        $query_exclusao = $mysqli->prepare($sql_exclusao) or die($mysqli->error);
        $query_exclusao->execute();

        if($query_exclusao){
?>
            <h1>Cliente deletado com sucesso!</h1>
            <nav><a href="../consultas/clientes.php">Voltar à lista de consulta</a></nav><hr><br>
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
    <title>Excluir Cliente</title>
</head>
<body>
    <header>
        <h1>Tem certeza que deseja excluir os dados de <?=$cliente['nome'];?>?</h1>
    </header>
    <main>
        <form action="" method="post">
            <p><button type="submit" name="sim" value="1">Sim</button></p>
            <p><button><a href="../consultas/clientes.php">Não</a></button></p>
        </form>
    </main>
</body>
</html>