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
    <title>Consulta Serviços</title>
</head>
<body>
    <header>
        <h1>Lista de Serviços Cadastrados</h1>
        <nav><a href="consulta.php">Voltar</a> | <a href="../cadastros/servicos.php">Cadastrar novo serviço</a></nav><hr><br>
    </header>
    <main>
        <section>
            <table border="1" cellpadding="7">
                <thead>
                    <th>OS</th>
                    <th>Tipo</th>
                    <th>Máquina</th>
                    <th>Fabricante</th>
                    <th>Modelo</th>
                    <th>Serviço</th>
                    <th>Comentário</th>
                    <th>Preço</th>
                    <th>Cliente</th>
                    <th>Empresa</th>
                    <th>Cidade</th>
                    <th>Data</th>
                    <th>Ação</th>
                </thead>
                <tbody>
                <?php 
                    $sql_servicos = "SELECT * FROM servicos";
                    $query_servicos = $mysqli->query($sql_servicos) or die($mysqli->error);
                    $num_servicos = $query_servicos->num_rows;

                    if($num_servicos == 0){
                ?>
                    <td colspan="13">Não há serviços cadastrados. <a href="../cadastros/servicos.php">Cadastre</a>.</td>
                <?php 
                    }else{
                        while($servico = $query_servicos->fetch_assoc()){
                            $preco = formataPreco($servico['preco']);
                            $data = formataData($servico['data']);
                ?>
                    <tr>
                        <td><?=$servico['os']; ?></td>
                        <td><?=$servico['tipo']; ?></td>
                        <td><?php if(!empty($servico['maquina'])) { echo $servico['maquina']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?php if(!empty($servico['fabricante'])) { echo $servico['fabricante']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?php if(!empty($servico['modelo'])) { echo $servico['modelo']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?=$servico['servico']; ?></td>
                        <td><?php if(!empty($servico['comentario'])) { echo $servico['comentario']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?=$preco; ?></td>
                        <td><?php if(!empty($servico['cliente'])) { echo $servico['cliente']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?php if(!empty($servico['empresa'])) { echo $servico['empresa']; }else{ echo "<p class=\"error\">Não informado</p>"; }?></td>
                        <td><?=$servico['cidade']; ?></td>
                        <td><?=$data; ?></td>
                        <td>
                            <p>
                                <button><a href="../editar/editar-servico.php?os=<?=$servico['os'];?>">Editar</a></button>
                                <button><a href="../editar/excluir-servico.php?os=<?=$servico['os'];?>">Excluir</a></button>
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