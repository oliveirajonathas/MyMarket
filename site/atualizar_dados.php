<?php
	session_start();
	//Importanto as bibliotecas de funções para configuração, conexão com o Banco de Dados e Realização de Querys
	require_once('config.php');
	require_once('connection.php');
	require_once('database.php');

	//Recuperando o nome do usuário atualmente logado para uma navegação interativa    
    $cpf_usuario = $_SESSION['cpf'];
    $consulta_nome = DBRead("consumidor", "WHERE cpf = $cpf_usuario", "nome");
    $nome_usuario = $consulta_nome[0]['nome'];    

	if (isset($_POST['atualizar'])) {
				
        $email            = $_POST['email'];
        $senha            = $_POST['senha'];
		
        $atualizacao = DBUpdate('consumidor', "WHERE nome = '$nome_usuario' AND cpf = '$cpf_usuario'", "email", "'$email'");
        $atualizacao = DBUpdate('consumidor', "WHERE nome = '$nome_usuario' AND cpf = '$cpf_usuario'", "senha", "'$senha'");
            
		if (!$atualizacao) {
                
		  echo "<script>alert('Atualização realizada com Sucesso!')</script>";
                
		}
		  
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MyMarket</title>
<link href="estilo.css" rel="stylesheet"/>
</head>
<body>
<div id="conteudo">
	<div id="topo">    
    </div>
    
    <div id="caixa">
    	<div class="atualizar">
        <h2>Atualizar Dados</h2>
        <form name="cadastrousuario" action="" method="post">
            <label for="inome" class="nome">Nome:&nbsp&nbsp&nbsp&nbsp</label><input type="text" name="nome" id="inome" class="form1" value="<?php echo $nome_usuario ?>" readonly="true"><br>
            <label for="icpf" class="cpf">CPF:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label><input type="number" name="cpf" id="icpf" class="form1" value="<?php echo $cpf_usuario ?>" readonly="true"><br>
            <label for="iemail" class="email">E-mail:&nbsp&nbsp&nbsp</label><input type="email" id= "iemail" class="form1" name="email" placeholder="email@email"><br>
            <label for="isenha" class="senha">Senha:&nbsp&nbsp&nbsp&nbsp</label><input type="password" id="isenha" class="form1" name="senha"><br><br>
            <input type="submit" value="ATUALIZAR" name="atualizar">
        </form><br/>
        <a href="area_do_consumidor.php">VOLTAR</a>
        </div>
    </div>
</div>
</body>
</html>
