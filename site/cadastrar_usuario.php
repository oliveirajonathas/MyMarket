<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="estilo.css"/>
</head>
<body>
<div id="conteudo">
<div id="topo"></div>
<?php
    
    require 'config.php';
    require 'connection.php';
    require 'database.php';
    
    if (isset($_POST["cad_usuario"])){
    
    $cpf            = $_POST['cpf'];
    $nome           = $_POST['nome'];    
    $email          = $_POST['email'];
    $senha          = $_POST['senha'];

    $consulta = DBRead('consumidor', null, 'cpf' );
    $tamanho = count($consulta);
    
    for ($i=0; $i < $tamanho; $i++) { 
        $resultado = $consulta[$i]['cpf'];
        if ($resultado == $cpf) {
            echo "<h1 align='center'>Você já possui cadastro!</h1>";
            echo "<p align='center'><a  href='index.html'>VOLTAR</a></p><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
        }
    }   

    $consumidor = array(

        'cpf'         => $cpf,
        'nome'        => $nome,
        'email'       => $email,
        'senha'       => $senha 
    );

    $resposta = DBCreate('consumidor', $consumidor);

    if ($resposta){

        echo "<script>alert('Cadastro Realizado com Sucesso!')</script>";
        echo "<a href='login_consumidor.php'>FAZER LOGIN</a>";

    }else{

        echo "Tente novamente mais tarde!";

    }
    }else{
        if(isset($_POST["cad_mercado"])){            

                    $cnpj           = $_POST['cnpj'];
                    $nome           = $_POST['nome'];    
                    $rua            = $_POST['rua'];
                    $bairro         = $_POST['bairro'];
                    $cidade         = $_POST['cidade'];
                    $email          = $_POST['email'];
                    $senha          = $_POST['senha'];
                    
                    $consulta = DBRead('mercado', null, 'cnpj' );
                    $tamanho = count($consulta);
    
                    for ($i=0; $i < $tamanho; $i++) { 
                        $resultado = $consulta[$i]['cnpj'];
                        if ($resultado == $cnpj) {
                            echo "<h1 align='center'>Você já possui cadastro!</h1>";
                            echo "<p align='center'><a  href='index.html'>VOLTAR</a></p><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                        }
                    }   

            $mercado = array(

                'cnpj'        => $cnpj,
                'nome'        => $nome,
                'rua'         => $rua,
                'bairro'      => $bairro,
                'cidade'      => $cidade,  
                'email'       => $email,
                'senha'       => $senha,
                );

            $resposta = DBCreate('mercado', $mercado);

            if ($resposta){

                echo "<script>alert('Cadastro Realizado com Sucesso!')</script>";
                                

            }else{

                echo "Tente novamente mais tarde!";

            }
            
            header("Location: login_mercado.php");
        }
    }
    
    
         
?>
</div>
</body>
</html>
