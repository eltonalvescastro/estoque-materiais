<?php
/**
 * Projeto de aplicação CRUD utilizando PDO - Cadastro de materiais
 *
 * Elton Castro
 */
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id');
    $empresa = filter_input(INPUT_POST, 'empresa');
    $item = filter_input(INPUT_POST, 'item');
    $codigomaterial = filter_input(INPUT_POST, 'codigomaterial');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $estoque = filter_input(INPUT_POST, 'estoque');
    $saida = filter_input(INPUT_POST, 'saida');
    $qtdfinal = filter_input(INPUT_POST, 'qtdfinal');
    
} else if (!isset($id)) {
// Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
}
// Cria a conexão com o banco de dados
try {
    $conexao = new PDO("mysql:host=localhost;dbname=controle-estoque", "root", "");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "<p class=\"bg-danger\">Erro na conexão:" . $erro->getMessage() . "</p>";
}
// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $empresa != "") {
    try {
        if ($id != "") {
            $stmt = $conexao->prepare("UPDATE cadmateriais SET empresa=?, item=?,codigomaterial=?, descricao=?, estoque=?, saida=?, qtdfinal=? WHERE id = ?");
            $stmt->bindParam(8, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO cadmateriais (empresa, item, codigomaterial, descricao, estoque, saida, qtdfinal ) VALUES (?, ?, ?, ?, ?, ?, ? )");
        }
        $stmt->bindParam(1, $empresa);
        $stmt->bindParam(2, $item);
        $stmt->bindParam(3, $codigomaterial);
        $stmt->bindParam(4, $descricao);
        $stmt->bindParam(5, $estoque);
        $stmt->bindParam(6, $saida);
        $stmt->bindParam(7, $qtdfinal);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "<p class=\"bg-success\">Dados cadastrados com sucesso!</p>";
                $id = null;
                $empresa = null;
                $item = null;
                $codigomaterial = null;
                $descricao = null;
                $estoque = null;
                $saida      = null;
                $qtdfinal   = null; 
               
            } else {
                echo "<p class=\"bg-danger\">Erro ao tentar efetivar cadastro</p>";
            }
        } else {
            echo "<p class=\"bg-danger\">Erro: Não foi possível executar a declaração sql</p>";
        }
    } catch (PDOException $erro) {
        echo "<p class=\"bg-danger\">Erro: " . $erro->getMessage() . "</p>";
    }
}
// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM cadmateriais WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $empresa = $rs->empresa;
            $item = $rs->item;
            $codigomaterial = $rs->codigomaterial;
            $descricao = $rs->descricao;
            $estoque = $rs->estoque;
            $saida = $rs->saida;
            $qtdfinal = $rs->qtdfinal;

        } else {
            echo "<p class=\"bg-danger\">Erro: Não foi possível executar a declaração sql</p>";
        }
    } catch (PDOException $erro) {
        echo "<p class=\"bg-danger\">Erro: " . $erro->getMessage() . "</p>";
    }
}

// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM cadmateriais WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "<p class=\"bg-success\">Registo foi excluído com êxito</p>";
            $id = null;
        } else {
            echo "<p class=\"bg-danger\">Erro: Não foi possível executar a declaração sql</p>";
        }
    } catch (PDOException $erro) {
        echo "<p class=\"bg-danger\">Erro: " . $erro->getMessage() . "</a>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge" >
        <title>Controle de Materiais</title>
        <link href="assets/css/bootstrap.css" type="text/css" rel="stylesheet" />
        <script src="assets/js/bootstrap.js" type="text/javascript" ></script>
    </head>
    <body>
        <div class="container">
            <header class="row">
                <br />
            </header>
            <article>
                <div class="row">
                    <form action="?act=save" method="POST" name="form1" class="form-horizontal" >
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span class="panel-title">Controle de Estoque de Material</span>
                            </div>
                            <div class="panel-body">

                                <input type="hidden" name="id" value="<?php
                                // Preenche o id no campo id com um valor "value"
                                echo (isset($id) && ($id != null || $id != "")) ? $id : '';

                                ?>" />
                                <div class="form-group">
                                    <label for="empresa" class="col-sm-1 control-label">Empresa:</label>
                                    <div class="col-md-2">
                                        <input type="text" name="empresa" value="<?php
                                        // Preenche o nome no campo empresa com um valor "value"
                                        echo (isset($empresa) && ($empresa != null || $empresa != "")) ? $empresa : '';

                                        ?>" class="form-control"/>
                                    </div>
                                    <label for="item" class="col-sm-4 control-label">Item:</label>
                                    <div class="col-md-1">
                                        <input type="text" name="item" value="<?php
                                        echo (isset($item) && ($item != null || $item != "")) ? $item : '';
                                        ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="codigomaterial" class="col-sm-2 control-label">Código do Material:</label>
                                    <div class="col-md-1">
                                        <input type="text" name="codigomaterial" value="<?php
                                        echo (isset($codigomaterial) && ($codigomaterial != null || $codigomaterial != "")) ? $codigomaterial : '';

                                        ?>" class="form-control" />
                                    </div>
                                    <label for="descricao" class="col-sm-4 control-label">Descrição:</label>
                                    <div class="col-md-4">
                                        <input type="text" name="descricao" value="<?php
                                        echo (isset($descricao) && ($descricao != null || $descricao != "")) ? $descricao : '';

                                        ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="estoque" class="col-sm-1 control-label">Estoque:</label>
                                    <div class="col-md-4">
                                        <input type="int" name="estoque" value="0"<?php
                                    

                                        echo (isset($estoque) && ($estoque != null || $estoque != "")) ? $estoque : '';

                                        ?>" class="form-control" />
                                    </div>
                                    <label for="saida" class="col-sm-2 control-label">Saída:</label>
                                    <div class="col-md-2">
                                        <input type="text" name="saida" value="0"<?php
                                        echo (isset($saida) && ($saida != null || $saida != "")) ? $saida : '';

                                        ?>" class="form-control" />
                                    </div> 
                                     <label for="qtdfinal" class="col-sm-1 control-label">Quantidade final:</label>
                                    <div class="col-md-1">
                                        <input type="text" name="qtdfinal" value=""<?php
                    
                                        echo (isset($qtdfinal) && ($qtdfinal != null || $qtdfinal != "")) ? $qtdfinal : '';
                                      

                                        ?>" class="form-control" />
                                    </div> 
                                    
                                </div>
                               
                            </div>
                         
                            <div class="panel-footer">
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-primary" /><span class="glyphicon glyphicon-ok"></span> salvar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="panel panel-default">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Item</th>
                                    <th>Código do Material</th>
                                    <th>Descrição</th>
                                    <th>Estoque</th>
                                     <th>Saída</th>
                                    <th>Quantidade Final</th>
                                    <!-- <th>Ações</th>  -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /**
                                 *  Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                                 */
                                try {
                                    $stmt = $conexao->prepare("SELECT * FROM cadmateriais");
                                    if ($stmt->execute()) {
                                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {

                                            ?><tr>
                                                <td><?php echo $rs->empresa; ?></td>
                                                <td><?php echo $rs->item; ?></td>
                                                <td><?php echo $rs->codigomaterial; ?></td>
                                                <td><?php echo $rs->descricao; ?></td>
                                                <td><?php echo $rs->estoque; ?></td>
                                                <td><?php echo $rs->saida; ?></td>
                                                <td><?php echo $rs->qtdfinal; ?></td> 
                                                <td><center>
                                            <a href="?act=upd&id=<?php echo $rs->id; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span> Editar</a>
                                            <a href="?act=del&id=<?php echo $rs->id; ?>" class="btn btn-danger btn-xs" ><span class="glyphicon glyphicon-remove"></span> Excluir</a>
                                        </center>
                                        </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "Erro: Não foi possível recuperar os dados do banco de dados";
                                }
                            } catch (PDOException $erro) {
                                echo "Erro: " . $erro->getMessage();
                            }

                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </div>
    </body>
</html>
