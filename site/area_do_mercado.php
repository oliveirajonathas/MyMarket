<?php

    session_start();/*Sempre que estivermos trabalhando com sessões, o primeiro comando usado nos scripts php deve ser o session_start()*/
    if(isset($_GET['deslogar'])){/*No link SAIR criado abaixo foi passada a queryString "deslogar", e esta foi passada por url.
    Então se houver na URL a palavra deslogar, isso significa que o usuário clicou em SAIR, o que significa que o mesmo deseja sair do
    sistema. Assim a sessão é destruída e o mesmo é redirecionado para a página inicial de autenticação*/
        unset($_SESSION['cnpj']);
        session_destroy();
        header("Location: login_mercado.php");
    }

    if(!isset($_SESSION['UsuarioLog'])){/*Aqui é feito o teste: caso NÃO EXISTA a sessão de nome UsuarioLog, definida na página de login
    isso significa que ou o usuário não digitou login e senha corretos, ou não é cadastrado, ou tentou burlaro sistema via URL... de
    qualquer maneira, o mesmo NÃO TERA ACESSO ao Painel do Usuário, sendo redirecionado para a página de validação login_mercado.php. No fim
    a sessão é destruída*/        
        header("Location:login_mercado.php");
        session_destroy();
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
        $cnpj_usuario = $_SESSION['cnpj'];
        $consulta_nome = DBRead("mercado", "WHERE cnpj = $cnpj_usuario", "nome");
        $nome_usuario = $consulta_nome[0]['nome'];
    ?>
    <link rel="stylesheet" type="text/css" href="estilo.css"/>
    <meta charset="UTF-8">
    <title>Área do Usuário</title>
</head>
<body>
<div id="conteudo">
    <div id="topo"></div>
<a href="login_mercado.php">Login</a>>Area do Usuário<br/><br/>

    <h1>Olá <?php echo $nome_usuario ?>.</h1>
    <h2>Você está na área do Usuário</h2>

 <div id="menu">
        <h3>MENU</h3>
        <h4><li><a href="M_atualizar_dados.php">Atualizar Dados Cadastrais</a></li></h4><br/>
        <h4><li><a href="cadastro_produto.php">Cadastrar Produto</a></li></h4><br/>
        <h4><li><a href="atualizar_produtos.php">Atualizar Produtos Cadastrados</a></li></h4><br/>
        <h4><li><a href="M_excluirconta.php">Excluir Conta</a></li></h4><br/>
    </div>
    
    <div id="conteudo2">
        <h3>Ao lado está o MENU de opções.</h3>
        <h3>Entenda o que pode fazer em cada caso:</h3>
        <p><li>Atualizar Dados Cadastrais: alterar informações como nome, e-mail e senha.</li></p>
         <p><li>Cadastrar Produto: alimentar a nossa base de dados com os produtos que tem a oferecer.</li></p>
         <p><li>Atualizar Produtos Cadastrados: alterar dados como preço, quantidade ou tipo de produto cadastrado.</li></p>
         <p><li>Excluir Conta: deletar a sua conta.</li></p>
    
    </div>

    <div id="rodape">
        <a href="?deslogar">SAIR</a>
    </div>
</div>     
</body>
</html>