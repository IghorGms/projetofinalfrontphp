<?php
session_start();

// Função para verificar as permissões do usuário
function verificarPermissao($tipo_permitido) {
  if ($_SESSION['tipo_usuario'] !== $tipo_permitido) {
      echo "Você não tem permissão para acessar esta página.";
      exit();
  }
}

// Verificar se o usuário é um paciente
verificarPermissao('ADMIN');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
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


    <div class="header">
        <h>Area do Administrador</h>
        <p>Gerenciamento de usuário</p>
    </div>
    <?php
        include "conexao.php";

      $sql2 = "SELECT
      id_paciente,
      nome_completo,
      numero_telefone,
      permissao,
      bloqueado,
      nome_usuario
      from id21615508_projetophp.pacientes 
      where nome_usuario != 'ADMIN';
";

      $sql="SELECT
      id_medico,
      nomeSobrenome,
      numero_telefone,
      crm,
      especialidade,
      permissao,
      bloqueado,
      nome_usuario
      from id21615508_projetophp.medicos
      where nome_usuario != 'ADMIN';";

$resultado = $conn->query($sql);

      if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
          echo "<div class='user-card card'>";
          echo "<div class='card-content'>";
          echo "<p class='title'>Dr." . $linha["nomeSobrenome"] . "</p>";
          echo "<p class='subtitle'>Função: ". $linha["permissao"]."</p>";
          echo "<p class='subtitle'>CRO: ". $linha["crm"]. "</p>";  
          echo "<p class='subtitle'>Especialidade: ". $linha["especialidade"]. "</p>";  

          if ($linha["bloqueado"] == 1) {
              echo "<p class='is-blocked' style='color: red'>Usuário Bloqueado</p>";
              // Botão para reativar o usuário
              echo "<form method='post' action='reativar_usuario.php'>";
              echo "<input type='hidden' name='usuario_id' value='" . $linha["id_medico"] . "'>";
              echo "<button type='submit' class='button is-success'>Reativar</button>";
              echo "</form>";
          } else {
              echo "<div class='buttons'>";
              echo "<button class='button is-success'>Ativar</button>";
              echo "<form method='post' action='bloquear_usuario.php'>";
              echo "<input type='hidden' name='usuario_id' value='" . $linha["id_medico"] . "'>";
              echo "<button type='submit' class='button is-warning'>Bloquear</button>";
              echo "</form>";
              echo "</div>";
          }
          echo "</div>";
          echo "</div>";
        }
      }
          
          $resultado2 = $conn->query($sql2);
          
      if (mysqli_num_rows($resultado2) > 0) {
        while ($linha2 = mysqli_fetch_assoc($resultado2)){
          echo "<div class='user-card card'>";
          echo "<div class='card-content'>";
          echo "<p class='title'>Paciente " . $linha2["nome_completo"] . "</p>";
          echo "<p class='subtitle'>Função: ".$linha2["permissao"]."</p>";
          echo "<p class='subtitle'>Número: ".$linha2["numero_telefone"]."</p>";
          
          $sqlFeedback = "SELECT feedback FROM id21615508_projetophp.feedBack WHERE id_paciente = " . $linha2["id_paciente"];
          $resultadoFeedback = $conn->query($sqlFeedback);

          if (mysqli_num_rows($resultadoFeedback) > 0) {
            echo "<div class='Mensagem-Feedback'>";
            echo "<p class='subtitle'>Feedback:</p>";
            while ($linhaFeedback = mysqli_fetch_assoc($resultadoFeedback)) {
                echo "<p>" . $linhaFeedback["feedback"] . "</p>";
            echo "</div>";
                }
            }
      
          if ($linha2["bloqueado"] == 1) {
              echo "<p class='is-blocked' style='color: red'>Usuário Bloqueado</p>";
              // Botão para reativar o usuário
              echo "<form method='post' action='reativar_paciente.php'>";
              echo "<input type='hidden' name='usuario_id' value='" . $linha2["id_paciente"] . "'>";
              echo "<button type='submit' class='button is-success'>Reativar</button>";
              echo "</form>";
          } else {
              echo "<div class='buttons'>";
              echo "<button class='button is-success'>Ativar</button>";
              echo "<form method='post' action='bloquear_paciente.php'>";
              echo "<input type='hidden' name='usuario_id' value='" . $linha2["id_paciente"] . "'>";
              echo "<button type='submit' class='button is-warning'>Bloquear</button>";
              echo "</form>";
              echo "</div>";
          }
      
          echo "</div>";
          echo "</div>";
        
      } 
}
?>

</body>
<script src="./scripts/script.js"></script>
</html>