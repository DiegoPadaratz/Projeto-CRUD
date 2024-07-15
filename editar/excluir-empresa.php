<?php 
    include("../conexao.php");

    $id = intval($_GET['id']);

    $sql_empresa = "SELECT * FROM empresas WHERE id='$id'";
    $query_empresa = $mysqli->query($sql_empresa);
    $empresa = $query_empresa->fetch_assoc();

    if(isset($_POST['sim'])){
        $sql_exclusao = "DELETE FROM empresas WHERE id = '$id'";
        $query_exclusao = $mysqli->prepare($sql_exclusao) or die($mysqli->error);
        $query_exclusao->execute();

        if($query_exclusao){
?>
            <h1>Empresa deletada com sucesso!</h1>
            <nav><a href="../consultas/empresas.php">Voltar à lista de consulta</a></nav><hr><br>
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
    <title>Excluir Empresa</title>
</head>
<body>
    <header>
        <h1>Tem certeza que deseja excluir os dados de <?=$empresa['nome'];?>?</h1>
    </header>
    <main>
        <form action="" method="post">
            <p><button type="submit" name="sim" value="1">Sim</button></p>
            <p><button><a href="../consultas/empresas.php">Não</a></button></p>
        </form>
    </main>
</body>
</html>