<?php

include "conexao.php";

// Inicializa a sessão (se ainda não estiver iniciada)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Função para adicionar mensagens de erro à sessão
function addError($message) {
    $_SESSION['errors'][] = "<span style='color: red;'>$message</span>";
}

$successMessage = $errorMessage = "";

if (isset($_POST['submit'])) {
    // Recupere os dados do formulário
    $nomeCompleto = $_POST['nomecompleto'];
    $email = $_POST['email'];
    $numeroTelefone = $_POST['numero'];
    $endereco = $_POST['endereco'];
    $nomeUsuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Validar o nome de usuário
    if (empty($nomeUsuario)) {
        addError("Por favor, preencha o campo Nome de Usuário.");
    } else {
        // Verifique a conexão
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        $sqlVerificaUsuarioMedico = "SELECT * FROM id21615508_projetophp.medicos WHERE nome_usuario = '$nomeUsuario'";
        $resultUsuarioMedico = $conn->query($sqlVerificaUsuarioMedico);
        
        // Verificar se o nome de usuário já existe em pacientes
        $sqlVerificaUsuarioPaciente = "SELECT * FROM id21615508_projetophp.pacientes WHERE nome_usuario = '$nomeUsuario'";
        $resultUsuarioPaciente = $conn->query($sqlVerificaUsuarioPaciente);
        
        // Verificar se o nome de usuário já existe em ambas as tabelas
        if ($resultUsuarioMedico->num_rows > 0) {
            addError("Nome de usuário já existe. Escolha outro.");
        } elseif ($resultUsuarioPaciente->num_rows > 0) {
            addError("Nome de usuário já existe. Escolha outro.");
        } 
      }

    // Validar o nome completo
    if (empty($nomeCompleto)) {
        addError("Por favor, preencha o campo Nome e Sobrenome.");
    }

    // Validar o email
    if (empty($email)) {
        addError("Por favor, preencha o campo Email.");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        addError("Formato de email inválido.");
    }

    // Validar o número de telefone
    if (empty($numeroTelefone)) {
        addError("Por favor, preencha o campo Número de Telefone.");
    }

    // Validar o endereço
    if (empty($endereco)) {
        addError("Por favor, preencha o campo Endereço.");
    }

    // Validar o nome de usuário
    if (empty($nomeUsuario)) {
        addError("Por favor, preencha o campo Nome de Usuário.");
    }

    // Validar a senha
    if (empty($senha)) {
        addError("Por favor, preencha o campo Senha.");
    }

    // Verifica se há erros na validação
    if (!empty($_SESSION['errors'])) {
      // Se houver erros, exibe as mensagens de erro no local apropriado no formulário
      echo "<div class='errors'>";
      foreach ($_SESSION['errors'] as $error) {
          echo $error . "<br>";
      }
      echo "</div>";
      session_destroy();
  } else {
 

      // Insira os dados no banco de dados
      $sqlInserirUsuario = "INSERT INTO id21615508_projetophp.pacientes (nome_completo, email, numero_telefone, endereco, nome_usuario, senha) 
                            VALUES ('$nomeCompleto', '$email', '$numeroTelefone', '$endereco', '$nomeUsuario', '$senha')";
 

 if ($conn->query($sqlInserirUsuario) === TRUE) {
    $_SESSION['PacienteInsert'] = "<span style='color: green;'>Cadastrado com sucesso!</span>";
  }

      $conn->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odontoclin</title> 
    <link rel="shortcut icon" href="./assets/dentinho.jpg" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="./styles/cadastroMedicoStyle.css">
</head>
<body>
    <section>
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item">
                   <img src="./assets/dentinho.jpg">
                </a>
                <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-end">
                    <a class="navbar-item" href="./index.html">Home</a>
                    <a class="navbar-item" href="./contato.php">Contato</a>
                    <a class="navbar-item" href="./cadastroPaciente.php">Cadastro Paciente</a>
                    <a class="navbar-item" href="./cadastroMedico.php">Cadastro Medico</a>
                    <a class="navbar-item" href="./login.php">Login</a>
                </div>
            </div>
        </nav>
    </section>

    <section>               
        <div class="container">
            <div class="formfield">
                <h2>Cadastro Paciente</h2>
                <?php
            if (isset($_SESSION['PacienteInsert'])) {
            echo $_SESSION['PacienteInsert'];
            unset($_SESSION['PacienteInsert']); // Limpar a mensagem após exibir
              }
            ?>
                <form action="cadastroPaciente.php" method="post">
                    <input type="text" name="nomecompleto" placeholder="Nome Completo" required><br>
                    <input type="email" name="email" placeholder="Email" required><br>
                    <input type="text" name="endereco" placeholder="Endereço" required><br>
                    <input type="text" name="numero" placeholder="Numero" required><br>
                    <input type="text" name="usuario" placeholder="Usuario" required><br>
                    <input type="password" class="pass__slot1" name="senha" placeholder="Senha" required><br>
                    <br><button type="submit" name="submit"> Enviar </button>
                </form>
            </div>
        </div>                    
    </section>

    <script src="./scripts/script.js"></script>

    <footer class="rodape">
        <ul class="rodape__list">
            <li class="list__link">
                <a href="##">idioma</a>
            </li>
            <li class="list__link">
                <a href="##">dispositivos compatíveis</a>
            </li>
            <li class="list__link">
                <a href="##">contrato de assinatura</a>
            </li>
            <li class="list__link">
                <a href="##">politica de privacidade</a>
            </li>
            <li class="list__link">
                <a href="##">protecao de dados no brasil</a>
            </li>
            <li class="list__link">
                <a href="##">anuncios personalizados</a>
            </li>
            <li class="list__link">
                <a href="##">ajuda</a>
            </li>
        </ul>

        <p class="rodape__text">
            Este site foi desenvolvido pela turma do 4 periodo, noite, uninassau.
        </p>
        <p class="rodape__text">
            Alunos:<br> Ana Paula Ferreira Pessoa - 01538280 <br> Carlos Augusto Nogueira Duarte - 01532620 <br> Ighor Gomes Gonçalves - 24010714 <br> Maximino Coelho da Silva - 01374898 <br> Pedro Augusto Borges Quintas - 01535444.
        </p>
    </footer>

</body>

</html>