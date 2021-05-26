<?php
    
    session_start();//Inicia-se uma sessão

    require 'config.php';
    require 'connection.php';
    require 'database.php';

    if (isset($_POST['cadastrar'])){
        /*Abaixo os valores passados via formulário são armazenados nas variáveis*/
    $data = date('d/m/y');//a função date retorna a data atual do sistema no formato DD/MM/AAAA
    $data = implode('-',array_reverse(explode('/',$data)));//Muda para o formado AAAA-MM-DD compatível com o banco de dados
   
    $cnpj_usuario       = $_SESSION['cnpj'];  
    $nome_prod        	= $_POST['nome_prod'];
    $marca    		    = $_POST['marca'];	
    $qtd               	= $_POST['qtd'];
    $unidade         	= $_POST['unidade'];
    $valor		        = $_POST['valor'];
    
    /*Agora é montada uma array cujos índices possuem os mesmos nomes dos campos da tabela de produtos no BD*/
    $produtos = array(

        'cnpj_usuario'       => $cnpj_usuario, 
        'nome_prod'          => strtoupper($nome_prod),
        'marca'              => $marca,
        'qtd'    	         => $qtd,
        'unidade'            => $unidade,  
	    'valor'  	         => str_replace(",", ".", $valor),
        'data'               => $data   

         
    );
    //Verificando se já existe o produto informado cadastrado. Se sim, o usuário é orientado a seguir para a tela de atualização.
    $verificacao = DBRead("produto", "WHERE cnpj_usuario = '$cnpj_usuario' AND nome_prod = '$nome_prod'");
    
    if ($verificacao) {
        echo "Produto já cadastrado. Favor, atualize as informações na área de atualização! <br/>";
    }else{

        $resposta = DBCreate('produto', $produtos);//Aqui é chamada a função que INSERE os dados no Banco.

    if ($resposta){//Se há conteúdo no array $resposta, então os dados foram cadastrados.
        echo "<script>alert('Produto cadastrado com Sucesso!')</script>";
    }else{
        echo "Tente novamente mais tarde!";
    }

    }
    
    

    /*Abaixo é feita a leitura dos dados cadastrados pelo usuário atualmente logado no sistema*/
    $registros = DBRead('produto JOIN mercado', "ON produto.cnpj_usuario = mercado.cnpj WHERE cnpj = $cnpj_usuario", "mercado.nome, produto.nome_prod");
    }    
                  
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel</title>
    <link rel="stylesheet" type="text/css" href="estilo.css"/>
</head>
    <!DOCTYPE html>

<body>

<div id="conteudo">
    <div id="topo"></div>
    <h1>Cadastro de Produtos</h1>

<h2>Realize agora o cadastro dos produtos ofertados:</h2>

<form method="post" action="">
    <fieldset><legend>Seu produto</legend>
        <br/>
        Nome: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" name="nome_prod" id="inome" placeholder="O que você tem a oferecer?" required="" /><br/><br/>
        
        Marca: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp   &nbsp&nbsp&nbsp<input type="text" name="marca" id="imarca" placeholder="Fabricante" required> <br/><br/>
        
        Quantidade:&nbsp&nbsp<input type='number' name='qtd' id='iqtd' placeholder='Informe a quantidade' required='' />   
        
        <select name="unidade">            
            <option value="Litros">Litros</option>
            <option value="Unidades">Unidades</option>
            <option value="Quilogramas">Quilogramas</option>
            <option value="Gramas">Gramas</option>
            <option value="Pacotes">Pacotes</option>
        </select><br/><br/>                   
        
        Valor: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp R$<input type="number" step="0.01" name="valor"
        id="ivalor" placeholder="Ex.: R$00,00" required><br/><br/>
        
        <input type="submit" name="cadastrar" value="CADASTRAR PRODUTO"/>
    </fieldset>
</form>
    <a href="area_do_mercado.php">VOLTAR</a>
</div>
</body>
</html>