<?php
    session_start();/*indica ao servidor que estará trabalhando com sessão. As sessões são recursos importantes de segurança,
pois impedem que usuários acessem áres restritas via url (sem validação) e permitem que um usuário validado continue logado
a um sistema até que este escolha se deslogar*/
    
    include_once('config.php');
    include_once('connection.php');
    include_once('database.php');

    if (isset($_SESSION['UsuarioLog'])){/*Importante: SE o usuário já estiver logado, isso significa que a sessão JÁ EXISTE, e queremos
    mantê-lo no painel do usuário. Assim, caso uma validação já tenha sido feita, o usuário é redirecionado direto para o painel,
    não sendo necessário fazer login toda vez que para na página index.php*/
        header("Location: area_do_consumidor.php");
        die();
    }  

    if (isset($_POST['entrar'])){#se existir um post Entrar significa que o usuário clicou no botão ENTRAR
        $conn = DBConnect();
        $email = @mysqli_escape_string($conn, $_POST['email']);#função que protege o sistema de SQL Inject e captura o login
        $senha = @mysqli_escape_string($conn, $_POST['senha']);#função que protege o sistema de SQL Inject e captura a senha
        
        $teste = DBRead("consumidor","WHERE email = '$email' AND senha = '$senha'", "cpf, nome");

       if ($teste){
           $_SESSION['UsuarioLog'] = true; /*cria uma sessão, de nome UsuarioLog, onde, ANTES de o usuário ser redirecionado para o painel, verifica se o mesmo realizou a validação de acesso via login e senha*/
           $_SESSION['cpf']= $teste[0]['cpf'];
           
           header("location: area_do_consumidor.php");

       } else {
           
           echo "<script> alert('Não Encontrado')</script>";
       }
    }
    
?>
<html lang="pt-br">
<head>

	<link href="estilo.css" rel="stylesheet"/>
    <meta charset="UTF-8">
    <title>Área de Login</title>

</head>
<body>
<div id="conteudo">
    <div id="topo"></div>

<a href="index.html">Home</a>>Login<br/><br/>

    <form id="login" action="" method="POST"/>
        <fieldset>
            <legend>Sistema de Login - Consumidor</legend>            
            <p>
                Login:&nbsp <input type="text" name="email" size="23" placeholder="E-mail cadastrado" required/>
            </p>
            <p>
                Senha: <input type="password" name="senha" size="23" maxlength="8" placeholder="Máximo 8 dígitos" required/>
            </p>
            <p>
                <input type="submit" name="entrar" value="ENTRAR"/>
            </p>
        </fieldset>
</div> 
</body>
</html>