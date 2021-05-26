<?php
	session_start();
	//Importanto as bibliotecas de funções para configuração, conexão com o Banco de Dados e Realização de Querys
	require_once('config.php');
	require_once('connection.php');
	require_once('database.php');

	//Recuperando o nome do usuário atualmente logado para uma navegação interativa    
    $cnpj_usuario = $_SESSION['cnpj'];
    $consulta_nome = DBRead("mercado", "WHERE cnpj = $cnpj_usuario", "nome");
    $nome_usuario = $consulta_nome[0]['nome'];

		if (isset($_POST['deletar'])) {
            if($_POST['escolha']=='sim'){
                $atualizacao = DBDelete ("produto", "cnpj_usuario = '$cnpj_usuario'");
                $atualizacao = DBDelete("mercado", "cnpj = '$cnpj_usuario'");
                session_destroy();
		 	    if ($atualizacao) {                   
                   header("Location: index.html");
		 	    }
            }else if($_POST['escolha']=='nao'){
                header("Location: area_do_mercado.php");
            }
		 	
		 //var_dump($_POST);
                
        }
		  
	

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" type="text/css" href="estilo.css"/>
    <meta charset="UTF-8">
    <title>Área do Usuário</title>
</head>
<body>
<div id="conteudo">
<div id="topo"></div>
<a href="area_do_mercado.php">Area do Mercado</a>>Exclusão de Conta<br/><br/>

    <h1><?php echo $nome_usuario ?>, deseja excluir a sua conta?</h1>
    
    <form method="post" action="">
    	<fieldset><legend>Tem certeza que deseja excluir a sua conta permanentemente?</legend>
    	SIM <input type="radio" name="escolha" value="sim">
        NÃO <input type="radio" name="escolha" value="nao">
    	</fieldset><br/><br/>
    	<input type="submit" name="deletar" value="CONFIRMAR"><br/><br/>
    </form>

<a href="area_do_mercado.php">VOLTAR</a>
</div>     
</body>
</html>