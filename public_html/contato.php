<?php
session_start();
// Função para adicionar mensagens de erro à sessão
function addError($message) {
    $_SESSION['errors'][] = "<span style='color: red;'>$message</span>";
}

// Função para verificar se o usuário está logado
function verificarLogin() {
    if (!isset($_SESSION['id_paciente'])) {
        header("Location: login.php"); // Redirecionar para a página de login se o usuário não estiver logado
        exit();
    }
}

// Função para verificar as permissões do usuário
function verificarPermissao($tipo_permitido) {
    if ($_SESSION['tipo_usuario'] !== $tipo_permitido) {
        echo "Somente Pacientes podem dar FeedBacks!";
        exit();
    }
}

// Verificar se o usuário está logado
verificarLogin();

// Verificar se o usuário é um médico
verificarPermissao('paciente');

$id_paciente = $_SESSION['id_paciente'];
$nome_paciente = $_SESSION['nome_paciente']; // Supondo que você já tenha essa informação


include "conexao.php";

if (isset($_POST['submit'])) {
  $feedBack = $_POST['message'];

  if (empty($feedBack)) {
    addError("Por favor, preencha o campo FeedBack.");
  }else {
  // Verificar a conexão
  if ($conn->connect_error) {
      die("Erro na conexão com o banco de dados: " . $conn->connect_error);
  }

  $sqlVerificarFeedBack = "SELECT * FROM id21615508_projetophp.feedBack WHERE id_paciente = '$id_paciente'";
  $resultFeedBack = $conn->query($sqlVerificarFeedBack);

  if ($resultFeedBack->num_rows > 0) {
    addError("Você já enviou um FeedBack!");
}
if (!empty($_SESSION['errors'])) {
  // Se houver erros, exibir as mensagens de erro no local apropriado no formulário
  echo "<div class='errors'>";
  foreach ($_SESSION['errors'] as $error) {
      echo $error . "<br>";
  }
  echo "</div>";
  unset($_SESSION['errors']); // Remover a variável 'errors' da sessão
} else {
  // Inserir os dados no banco de dados
  $sqlInserirMedico = "INSERT INTO id21615508_projetophp.feedBack (id_paciente, feedBack)
                      VALUES ('$id_paciente', '$feedBack')";

  if ($conn->query($sqlInserirMedico) === TRUE) {
    $_SESSION['feedback_message'] = "<span style='color: green;'>FeedBack Enviado!</span>";
  }

  $conn->close();
}
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odontoclin</title>
    <link rel="stylesheet" href="./styles/contato.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="./assets/dentinho.jpg" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cabin+Condensed&family=Inter&family=Mooli&display=swap" rel="stylesheet">
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

    <div class="container">
        <h1 class="brand"><span>Odontoclin</h1>
        <div class="wrapper">
          <div class="company-info">
            <h3>Odontoclin</h3>
            <?php
            if (isset($_SESSION['feedback_message'])) {
            echo $_SESSION['feedback_message'];
            unset($_SESSION['feedback_message']); // Limpar a mensagem após exibir
              }
            ?>
            <ul>
              <li><i class="fa fa-road"></i> R. Carmelita Muniz de Araújo, 225 </li>
              <li><i class="fa fa-phone"></i> (81) 3413-4611</li>
              <li><i class="fa fa-envelope"></i> odontoclin@odonto.com.br </li>
            </ul>
          </div>
          <div class="contact">
            <h3>Mande-nos um feedBack! </h3>
            <form action="contato.php" method="post" id="contactForm">
              <p>
                <label>Nome:</label>
                <?php echo "<h7><strong>".$nome_paciente."</strong></h7>" ?>
              </p>

              <p class="full">
                <label>Conte-nos o que houve! </label>
                <textarea name="message" rows="5" id="message"></textarea>
              </p>
              <p class="full">
                <button name="submit" type="submit">Enviar</button>
              </p>
            </form>
          </div>
        </div>
      </div>
</body>

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
      Alunos:<br> Ana Paula Ferreira Pessoa - 01538280 <br> Carlos Augusto Nogueira Duarte - 01532620 <br> Ighor Gomes Gonçalves - 24010714 <br> Maximino Coelho da Silva - 01374898 <br> Pedro Augusto Borges Quintas - 01535444.
  </p>
  <p class="rodape__text1">
      Este site foi desenvolvido pela turma do 4 periodo, noite, uninassau. <br>
      Todos os direitos reservados.
  </p>
</footer>

<script src="./scripts/script.js"></script>

</html>