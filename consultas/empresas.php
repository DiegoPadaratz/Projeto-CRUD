<?php 
    include("../conexao.php");
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
    <title>Consulta Empresas</title>
</head>
<body>
    <header>
        <h1>Lista de Empresas Cadastradas</h1>
        <nav><a href="consulta.php">Voltar</a> | <a href="../cadastros/empresas.php">Cadastrar nova empresa</a></nav><hr><br>
    </header>
    <main>
        <section>
            <table border="1" cellpadding="10">
                <thead>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cidade</th>
                    <th>Endereço</th>
                    <th>Proprietário</th>
                    <th>CNPJ</th>
                    <th>Ação</th>
                </thead>
                <tbody>
                <?php 
                    $sql_empresas = "SELECT * FROM empresas";
                    $query_empresas = $mysqli->query($sql_empresas) or die($mysqli->error);
                    $num_empresas = $query_empresas->num_rows;

                    if($num_empresas == 0){
                ?>
                    <td colspan="10">Não há empresas cadastradas. <a href="../cadastros/empresas.php">Cadastre</a>.</td>
                <?php 
                    }else{
                        while($empresa = $query_empresas->fetch_assoc()){
                            $cnpj = formataCnpj($empresa['cnpj']);
                ?>
                    <tr>
                        <td><?=$empresa['id'];?></td>
                        <td><?=$empresa['nome'];?></td>
                        <td><?=$empresa['cidade'];?></td>
                        <td><?php if(!empty($empresa['endereco'])){ echo $empresa['endereco']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?php if(!empty($empresa['proprietario'])){ echo $empresa['proprietario']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?=$cnpj;?></td>
                        <td>
                            <p>
                                <button><a href="../editar/editar-empresa.php?id=<?=$empresa['id'];?>">Editar</a></button>
                                <button><a href="../editar/excluir-empresa.php?id=<?=$empresa['id'];?>">Excluir</a></button>
                            </p>
                        </td>
                    </tr>
                    <?php 
                    }
                }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>