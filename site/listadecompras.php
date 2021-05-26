<?php 
    session_start();/*Sempre que estivermos trabalhando com sessões, o primeiro comando usado nos scripts php deve ser o session_start()*/
    if(isset($_GET['deslogar'])){/*No link SAIR criado abaixo foi passada a queryString "deslogar", e esta foi passada por url.
    Então se houver na URL a palavra deslogar, isso significa que o usuário clicou em SAIR, o que significa que o mesmo deseja sair do
    sistema. Assim a sessão é destruída e o mesmo é redirecionado para a página inicial de autenticação*/
        unset($_SESSION['cpf']);
        session_destroy();
        header("Location: login_consumidor.php");
    }

    if(!isset($_SESSION['UsuarioLog'])){/*Aqui é feito o teste: caso NÃO EXISTA a sessão de nome UsuarioLog, definida na página de login
    isso significa que ou o usuário não digitou login e senha corretos, ou não é cadastrado, ou tentou burlaro sistema via URL... de
    qualquer maneira, o mesmo NÃO TERA ACESSO ao Painel do Usuário, sendo redirecionado para a página de validação login_consumidor.php. No fim
    a sessão é destruída*/        
        header("Location:login_consumidor.php");
        session_destroy();
    }
    
    //Importanto as bibliotecas de funções para configuração, conexão com o Banco de Dados e Realização de Querys
	require_once('config.php');
	require_once('connection.php');
	require_once('database.php');
    
    //Consulta pelos produtos cadastrados na tabela Produto e bairros cadastrados na tabela mercado
    $consulta_prod = DBRead("produto", "GROUP BY nome_prod", 'nome_prod');
    $consulta_bairro = DBRead("mercado", "GROUP BY bairro", 'bairro');
    
    //Transformando o array gerado em uma matriz para exibir na tela
	$tamanho = count($consulta_prod);//Número de elementos encontrados => Corresponde a cada produto cadastrado
    $tamanho1 = count($consulta_bairro);//Número de elementos encontrados => Corresponde a cada bairro cadastrado
    	
	//Criando um vetor com os produtos cadastrados
	for ($i=0; $i<$tamanho ; $i++) { 
		$array_produtos[$i] = $consulta_prod[$i]['nome_prod'];
	}
    
    //Criando um vetor com os bairros cadastrados
	for ($i=0; $i<$tamanho1 ; $i++) { 
		$array_bairro[$i] = $consulta_bairro[$i]['bairro'];	        
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php
    //Importanto as bibliotecas de funções para configuração, conexão com o Banco de Dados e Realização de Querys
        require_once('config.php');
        require_once('connection.php');
        require_once('database.php');
    //Recuperando o nome do usuário atualmente logado para uma navegação interativa    
        $cpf_usuario = $_SESSION['cpf'];
        $consulta_nome = DBRead("consumidor", "WHERE cpf = $cpf_usuario", "nome");
        $nome_usuario = $consulta_nome[0]['nome'];
    ?>
    <link rel="stylesheet" type="text/css" href="estilo.css"/>
    <meta charset="UTF-8">
    <title>Área do Usuário</title>
</head>
<body>
<div id="conteudo">
    <div id="topo"></div>
<a href="area_do_consumidor.php">Area do Consumidor</a>>Gerar Lista<br/><br/>

    <h1>Olá <?php echo $nome_usuario ?>.</h1>
    <h2>Escolha abaixo os produtos disponíveis e o bairro mais próximo a você:</h2>
    
    <form method="post" action="gerar_lista5.php">
    	<fieldset><legend>Selecione um produto</legend>
    	<?php
    			for ($i=0; $i < $tamanho; $i++) {
                    $j = $i + 1;
                    if ($j==$tamanho){
                        echo "<input type='checkbox' name='$i' value='$array_produtos[$i]' checked=''> $array_produtos[$i] <br/>";       
                        }elseif ($array_produtos[$i]<>$array_produtos[$j]){
                            echo "<input type='checkbox' name='$i' value='$array_produtos[$i]' checked=''> $array_produtos[$i] <br/>";
                        }
                    }                
            ?>	
    	
    	</fieldset><br/><br/>
    	<fieldset><legend>Informe o Bairro</legend>
    		<select name="bairro">            
                <?php
                    for ($i=0; $i < $tamanho1; $i++) {
                    $j = $i + 1;
                    if ($j==$tamanho){
                        echo "<option value='$array_bairro[$i]'>$array_bairro[$i]</option>";       
                        
                    }elseif ($array_bairro[$i]<>$array_bairro[$j]){
                             echo "<option value='$array_bairro[$i]'>$array_bairro[$i]</option>";
                        }
                    }                     
                ?>
            </select><br/><br/>
    	</fieldset><br/><br/>
        
    	<input type="submit" name="gerar" value="GERAR LISTA"><br/><br/>
    </form>

    
  <a href="?deslogar">SAIR</a>
    

</div>     
</body>
</html>