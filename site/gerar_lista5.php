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
            
            $consulta_pers[$i] = DBRead("mercado JOIN produto", "ON mercado.cnpj = produto.cnpj_usuario WHERE produto.nome_prod LIKE '%$produtos_selec1[$i]%' AND mercado.bairro = '$bairro' ", "mercado.nome, mercado.email, mercado.bairro, mercado.rua, mercado.cidade, produto.nome_prod, produto.qtd, produto.marca, produto.unidade, produto.valor, produto.data");
        }
        
        $tamanho_pers = count($consulta_pers);        
       
        //O algoritmo abaixo transformar o array multidimensional $consulta_pers em um vetor $consulta com todos os dados.
        $indice = 0;
        for($j=0; $j<$tamanho_pers; $j++){
            for($k=0; $k<count($consulta_pers[0]); $k++){
                $consulta[$indice] = $consulta_pers[$j][$k];
                $indice = $indice + 1;
            }
        }
        
        $tamanho = count($consulta);
        //var_dump($consulta);        
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


    <?php
    
    $controle_mercado = array();//Criada uma variável de controle para armazenar o nome do mercado cuja tabela de preços já foi gerada.
    $controle_saldo = array();
    
    //Abaixo o código para gerar as tabelas de preços de cada mercado.     
    for($i=0; $i<$tamanho; $i++){
        
        if (!in_array($consulta[$i]['nome'],$controle_mercado)){//Verifica se a tabela do mercado já foi gerada. Se não, a tabela é preenchida. Se sim, não é preenchida. 
        
        $mercado = $consulta[$i]['nome'];
        $email   = $consulta[$i]['email'];    
        $produto = $consulta[$i]['nome_prod'];
        $marca   = $consulta[$i]['marca'];
        $qtd     = $consulta[$i]['qtd'];
        $und     = $consulta[$i]['unidade'];
        $valor   = $consulta[$i]['valor'];
        $rua     = $consulta[$i]['rua'];
        $bairro  = $consulta[$i]['bairro'];
        $cidade  = $consulta[$i]['cidade'];
        $data    = date('d/m/Y', strtotime($consulta[$i]['data']));
        
        echo "<fieldset><legend><h1>Mercado: $mercado</h1></legend>";
        echo "<h2>Endereço: $rua, bairro $bairro</h2>";
        echo "<h3>Email para contato: $email</h3>";
        echo "<table id='produtos' cellpadding='10' cellspacing= '5' align='center'>";
        echo "<tr bgcolor='#cccccc'>";
            echo "<td>Produto</td>";
            echo "<td>Marca</td>";
            echo "<td>Quantidade</td>";
            echo "<td>Unidade</td>";
            echo "<td>Valor</td>";
            echo "<td>Data de Atualização</td>";
        echo"</tr>";
        echo "<tr bgcolor='#F5FFFA'>";//Preenchimento da primeira linha
            echo "<td>$produto</td>";
            echo "<td>$marca</td>";
            echo "<td>$qtd</td>";
            echo "<td>$und</td>";
            echo "<td>R$ $valor</td>";
            echo "<td>$data</td>";            
        echo"</tr>";
            
            $saldo1 = $valor;
            $saldo2 = 0;
            
        for ($j=$i+1; $j<$tamanho; $j++){//Preenchimento das linhas seguintes            
            
            $produto = $consulta[$j]['nome_prod'];
            $marca   = $consulta[$j]['marca'];
            $qtd     = $consulta[$j]['qtd'];
            $und     = $consulta[$j]['unidade'];
            $valor   = $consulta[$j]['valor'];
            $rua     = $consulta[$j]['rua'];
            $bairro  = $consulta[$j]['bairro'];
            $cidade  = $consulta[$j]['cidade'];
            $data    = date('d/m/Y', strtotime($consulta[$j]['data']));

            
            if ($consulta[$j]['nome']==$mercado){//Verifica se o Mercado se repete. 
                echo "<tr bgcolor='#F5FFFA'>";
                    echo "<td>$produto</td>";
                    echo "<td>$marca</td>";
                    echo "<td>$qtd</td>";
                    echo "<td>$und</td>";
                    echo "<td>R$ $valor</td>";
                    echo "<td>$data</td>";
                echo"</tr>";
                $saldo2 = $saldo2 + $valor;
            }
        }
        
        $saldo_total = $saldo1 + $saldo2;
        array_push($controle_saldo, $saldo_total);
        array_push($controle_mercado,$mercado);//o nome do mercado é armazenado ao fim do array $controle_mercado em cada laço
            
        echo "<tr>";
            echo "<td bgcolor='#F5FFFA'><b>TOTAL:</b></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td bgcolor='#F5FFFA'><b>R$ $saldo_total</b></td>";
            echo "<td></td>";
            echo"</tr>";
        echo "</table>";
        echo "</fieldset>";
    }        
  }    
        
    $tamanho_controle = count($controle_saldo);
        
    $melhor_mercado = $controle_mercado[0];
    $melhor_saldo   = $controle_saldo[0];
            
    for ($i=1; $i<$tamanho_controle; $i++){        
        
        if($melhor_saldo > $controle_saldo[$i]){
            $melhor_saldo   = $controle_saldo[$i];
            $melhor_mercado = $controle_mercado[$i];            
        }        
    }
        echo "<script>alert('Melhor opção de compra: $melhor_mercado')</script>";
        echo "<h3>Melhor opção de compra: $melhor_mercado</h3>";
}//fim do isset 

?>
    
<a href="?deslogar">SAIR</a>
</div>     
</body>
</html>