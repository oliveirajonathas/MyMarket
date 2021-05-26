<?php

    session_start();/*Sempre que estivermos trabalhando com sessões, o primeiro comando usado nos scripts php deve ser o session_start()*/

    //Importanto as bibliotecas de funções para configuração, conexão com o Banco de Dados e Realização de Querys
    require_once('config.php');
    require_once('connection.php');
    require_once('database.php');

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
    
    /*O código abaixo resgata os valores passados via POST, e os armazena na variável $produtos_selec por meio da função implode que coloca todos os valores do POST em uma string separada por "- ". Em seguida, a função explode é usada para colocar os dados em um array, na mesma variável $produtos_selec, porém agora com índices ordenados. Como além dos produtos é colocado no array informações relativas ao bairro e ao valor do botão submity, o laço for trata de armazenar os dados de $produtos_selec na variável $produtos_selec1 excluindo os últimos dois valores não desejados */
	
    $produtos_selec = implode('- ', $_POST);
    $produtos_selec = explode('- ', $produtos_selec);
    $cont = count($_POST);
    
    //array $produto_select1 possui os PRODUTOS SELECIONADOS para a lista, com índices ordenados.
    for ($i=0; $i<$cont-2; $i++){
        $produtos_selec1[$i] = $produtos_selec[$i];
    }
    //A variável $bairro guarda a informação sobre o BAIRRO enviada via POST
    $bairro = $_POST['bairro'];
    //Con1 é guarda o número de elementos da variável $produtos_selec1
    $cont1 = count($produtos_selec1);    
    //var_dump($cont1);
    //var_dump($produtos_selec1);
    

    //O Código abaixo faz a consulta de acordo aos produtos selecionados em $prod
    if (isset($_POST['gerar'])) {
        for ($i=0; $i<$cont1; $i++){
            
            $prod = $produtos_selec1[$i];
            //echo $prod . '<br/>';
            
            $consulta_pers[$i] = DBRead("mercado JOIN produto", "ON mercado.cnpj = produto.cnpj_usuario WHERE produto.nome_prod LIKE '%$prod%' AND mercado.bairro = '$bairro' ", "mercado.nome, mercado.bairro, mercado.rua, mercado.cidade, produto.nome_prod, produto.qtd, produto.marca, produto.unidade, produto.valor");
            
            $consulta_valor[$i] = DBRead("mercado JOIN produto", "ON mercado.cnpj = produto.cnpj_usuario WHERE produto.nome_prod LIKE '%$prod%' AND mercado.bairro = '$bairro' ", "produto.valor");
                    
        }
		
        var_dump($consulta_valor);
        
        $tamanho_pers = count($consulta_pers);
         //var_dump($tamanho_pers);

		//$consulta_pers = DBRead();
		//var_dump($consulta_pers);
        for ($i=0; $i<$cont1; $i++){
            for ($j=0; $j<($cont1/2); $j++){
                //var_dump($consulta_pers[$i][$j]['nome']);
                $nome ['mercado'][$j] = $consulta_pers[$i][$j]['nome'];
                $nome_prod['nome_prod'][$i] = $consulta_pers[$i][$j]['nome_prod'];
                $valor['valor'][$i][$j] = $consulta_valor[$i][$j]['valor'];
                $rua['rua'][$j] = $consulta_pers[$i][$j]['rua'];
            }            
        }

    //var_dump($nome_prod['nome_prod']);
    //var_dump($nome['mercado']);
    //var_dump($rua['rua']);
    var_dump($valor['valor']);

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
<a href="listadecompras.php">Gerar Lista</a>>Listas<br/><br/>
<a href="?deslogar">SAIR</a>

    <?php 
        //var_dump($produtos_selec1);
        //var_dump($bairro);
        

	 
        for ($i=0; $i<$cont1; $i++){
            $produto[$i]= $nome_prod['nome_prod'][$i];         
        }
        
        for ($j=0; $j<($cont1/2); $j++){
            $mercado[$j] = $nome ['mercado'][$j]; 
        }
        
        for ($i=0; $i<$cont1; $i++){
            for ($j=0; $j<($cont1/2); $j++){
                $val[$i] = $valor['valor'][$i][$j];
                
            }
        }
        
        var_dump ($c = count ($val));
        
        
        
        //var_dump($produto);
        //var_dump($mercado);
        var_dump($val);
        //var_dump($valor);

        //var_dump($nome['mercado']);
         //var_dump($rua['rua']);
         //var_dump($valor['valor']);
         
        echo 	"<table id='produtos' cellpadding='10' cellspacing= '5' align='center'>";
		echo		"<tr bgcolor='#cccccc'>";
		echo			"<td>Mercado</td>";
		echo			"<td>Produto</td>";
		echo			"<td>Quantidade</td>";
		echo			"<td>Unidade</td>";
		echo  			"<td>Marca</td>";
		echo			"<td>Valor</td>";		
		echo		"</tr>";
       
        
        for ($i=0; $i<$cont1; $i++){
            for ($j=0; $j<$cont1/2; $j++){
                echo		"<tr>";
		        echo			"<td>$mercado[$j]</td>";
		        echo			"<td>$produto[$i]</td>";
		        echo			"<td></td>";
		        echo			"<td></td>";
		        echo			"<td></td>";
		        echo			"<td></td>";	
		        echo		"</tr>";
            }                             
        }
    }
?>    
        

    

</div>     
</body>
</html>