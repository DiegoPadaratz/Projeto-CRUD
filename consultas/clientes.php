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
    <title>Consulta Clientes</title>
</head>
<body>
    <header>
        <h1>Lista de Clientes Cadastrados</h1>
        <nav><a href="consulta.php">Voltar</a> | <a href="../cadastros/clientes.php">Cadastrar novo cliente</a></nav><hr><br>
    </header>
    <main>
        <section>
            <table border="1" cellpadding="10">
                <thead>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Cidade</th>
                    <th>Endereço</th>
                    <th>Empresa</th>
                    <th>CPF</th>
                    <th>CNPJ</th>
                    <th>Ação</th>
                </thead>
                <tbody>
                <?php 
                    $sql_clientes = "SELECT * FROM clientes";
                    $query_clientes = $mysqli->query($sql_clientes) or die($mysqli->error);
                    $num_clientes = $query_clientes->num_rows;

                    if($num_clientes == 0){
                ?>
                    <td colspan="10">Não há clientes cadastrados. <a href="../cadastros/clientes.php">Cadastre</a>.</td>
                <?php 
                    }else{
                        while($cliente = $query_clientes->fetch_assoc()){
                            $telefone = formataTelefone($cliente['telefone']);
                            $cpf = formataCpf($cliente['cpf']);
                            $cnpj = formataCnpj($cliente['cnpj']);
                ?>
                    <tr>
                        <td><?=$cliente['id'];?></td>
                        <td><?=$cliente['nome'];?></td>
                        <td><?=$telefone;?></td>
                        <td><?php if(!empty($cliente['email'])) { echo $cliente['email']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?=$cliente['cidade'];?></td>
                        <td><?php if(!empty($cliente['endereco'])) { echo $cliente['endereco']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?php if(!empty($cliente['empresa'])) { echo $cliente['empresa']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?php if(!empty($cliente['cpf'])) { echo $cpf; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?php if(!empty($cliente['cnpj'])) { echo $cnpj; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td>
                            <p>
                                <button><a href="../editar/editar-cliente.php?id=<?=$cliente['id'];?>">Editar</a></button>
                                <button><a href="../editar/excluir-cliente.php?id=<?=$cliente['id'];?>">Excluir</a></button>
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