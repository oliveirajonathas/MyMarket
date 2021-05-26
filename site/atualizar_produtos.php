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

	//É feita uma consulta na tabela de produtos com base no cnpj do usuário atualmente logado no sistema
	$cnpj_atual = $_SESSION['cnpj'];
	$consulta = DBRead("produto", "WHERE cnpj_usuario = $cnpj_atual GROUP BY nome_prod", 'nome_prod');
	
	//Transformando o array gerado em uma matriz para exibir na tela
	$tamanho = count($consulta);//Número de elementos encontrados => Corresponde a cada produto cadastrado
	
	//Criando um vetor com os produtos cadastrados
	for ($i=0; $i<$tamanho ; $i++) { 
		$array_produtos[$i] = $consulta[$i]['nome_prod'];
	}

	//Criando uma string de produtos cadastrados
	$produtos = implode('- ', $array_produtos);

	if (isset($_POST['atualizar'])) {
		
		$produto        = $_POST['produto'];
		$quantidade     = $_POST['quantidade'];
        $valor          = $_POST['valor'];
		
        $qtd_antes      = DBRead('produto', "WHERE nome_prod LIKE '$produto' AND cnpj_usuario LIKE '$cnpj_atual' ", "qtd");
		$qtd_atual      = $quantidade;
        /*var_dump($qtd_antes[0]['qtd']);
        var_dump($quantidade);
        var_dump($qtd_atual);*/
        $valor_antes    = DBRead('produto', "WHERE nome_prod LIKE '$produto' AND cnpj_usuario LIKE '$cnpj_atual' ", "valor");
        $valor_atual    = $valor;
		$consulta_id = DBRead("produto", "WHERE cnpj_usuario LIKE '$cnpj_atual' AND nome_prod LIKE '$produto'", "id");
		//var_dump($consulta_id);

		if ($qtd_atual<=0) {

		 	$id = $consulta_id[0]['id'];
		 	$atualizacao = DBDelete("produto", "id = '$id'");
		 	if ($atualizacao) {
		 		echo "<script>alert('Atualização realizada com Sucesso!')</script>";
		 	}
		 }else{
		 	 
		 	$atualizacao = DBUpdate('produto', "WHERE nome_prod = '$produto' AND cnpj_usuario = '$cnpj_atual'", "qtd", "'$qtd_atual'");
            $atualizacao = DBUpdate('produto', "WHERE nome_prod = '$produto' AND cnpj_usuario = '$cnpj_atual'", "valor", "'$valor_atual'");            
            
		 	if (!$atualizacao) {
                
		 		echo "<script>alert('Atualização realizada com Sucesso!')</script>";
                
		 	}
		 } 
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
<a href="area_do_mercado.php">Area do Mercado</a>>Atualização de Produtos<br/><br/>

    <h1><?php echo $nome_usuario ?>, deseja alterar algum produto?</h1>
    
    <form method="post" action="">
    	<fieldset><legend>Selecione um produto</legend>
    	PRODUTO: <select name="produto" required="">
    		<?php
    			for ($i=0; $i < $tamanho; $i++) { 
    			echo "<option>$array_produtos[$i]</option>";
    			}
    		
    		?>	
    	</select>
    	</fieldset><br/><br/>
    	<fieldset><legend>Informe a quantidade</legend>
    		QUANTIDADE: <input type="number" name="quantidade" required>
            <select name="unidade">            
            <option value="Litros">Litros</option>
            <option value="Unidades">Unidades</option>
            <option value="Quilogramas">Quilogramas</option>
            <option value="Gramas">Gramas</option>
            <option value="Pacotes">Pacotes</option>
        </select><br/><br/>
    	</fieldset><br/><br/>
        <fieldset><legend>Informe o Valor</legend>
            VALOR: R$<input type="number" name="valor" step="0.01" required>
    	</fieldset><br/><br/>
    	<input type="submit" name="atualizar" value="ATUALIZAR"><br/><br/>
    </form>

<a href="area_do_mercado.php">VOLTAR</a>
</div>     
</body>
</html>