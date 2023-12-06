<?php
session_start();

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
        echo "Você não tem permissão para acessar esta página.";
        exit();
    }
}

// Verificar se o usuário está logado
verificarLogin();

// Verificar se o usuário é um paciente
verificarPermissao('paciente');
$idPaciente = $_SESSION['id_paciente'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odontoclin</title>
    <link rel="stylesheet" href="./styles/areaPacStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="shortcut icon" href="./assets/dentinho.jpg" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cabin+Condensed&family=Inter&family=Mooli&display=swap" rel="stylesheet">
</head>

<body>
    <div>
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
    </div>

    <section class="section">
        <div class="container">
          <h class="head">Página do Paciente</h>
    
          <!-- Médicos e Especialidades -->
          <p class="subtitle">Médicos e Especialidades</p>
          <div class="columns is-multiline">
            <!-- Card de Médico -->
            <?php
            include "conexao.php";

            $sql = "SELECT m.id_medico, m.nomeSobrenome, m.especialidade, m.endereco_de_trabalho, m.nome_usuario, m.bloqueado
            FROM id21615508_projetophp.medicos AS m WHERE nome_usuario != 'ADMIN' AND bloqueado != '1'";

            $resultado = mysqli_query($conn, $sql);

            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    echo "<div class='column is-one-third'>";
                    echo "<div class='medico-card card' name='{$linha['id_medico']}' onclick=\"mostrarHorarios('Dr.{$linha['nomeSobrenome']}', {$linha['id_medico']})\">";
                    echo "<div class='card-content'>";
                    echo "<p class='title'>Dr.{$linha['nomeSobrenome']}</p>";
                    echo "<p class='subtitle'>{$linha['especialidade']}</p>";
                    echo "<p>Horário: Seg-Sex, 9h - 12h</p>";
                    echo "<p>Local: {$linha['endereco_de_trabalho']} / {$linha['especialidade']}</p>";
                    echo "</div>";
                    echo "</div>";
                    // Botões de agendamento
                    echo "<form action='agendamento.php' method='post'>";
                    echo "<input type='hidden' name='id_medico' value='{$linha['id_medico']}'>"; // Adiciona um campo oculto com o id_medico
                    echo "<label for='hora4'>09:00</label>";
                    echo "<input type='radio' id='hora4' name='horario' value='09:00'>";
                    echo "<label for='hora5'>10:00</label>";
                    echo "<input type='radio' id='hora5' name='horario' value='10:00'>";
                    echo "<label for='hora6'>11:30</label>";
                    echo "<input type='radio' id='hora6' name='horario' value='11:30'>";
                    echo "<label for='hora6'>12:00</label>";
                    echo "<input type='radio' id='hora6' name='horario' value='12:00'><br>";
                    echo "<button class='button is-primary' type='submit'>Agendar</button>";
                            
                    echo "</form>";
                    echo "</div>";
                }
            }
            ?>
          </div>
                
                <div class="button-box">
                  <button class='button-bot' onclick='telapacientefinal()'>Consultas</button>
                </div>

                <?php
      if (isset($_GET['message'])) {
          $message = urldecode($_GET['message']);
          echo "<p>$message</p>";
      }
      ?>
            </div>
            <!-- Adicione mais cards de médicos conforme necessário -->
                <Script>
                function telapacientefinal(){

                window.location.href = "telapacientefinal.php";

                }
                </Script>

        <script src="./scripts/script.js"></script> 
      </section>

</body>
</html>