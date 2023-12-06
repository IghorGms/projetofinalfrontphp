<?php
session_start();

// Função para verificar se o usuário está logado
function verificarLogin() {
    if (!isset($_SESSION['id_medico'])) {
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

// Verificar se o usuário é um médico
verificarPermissao('medico');

$id_medico = $_SESSION['id_medico'];

include "conexao.php";

// Inicia o tempo de prontuário
if (!isset($_SESSION['tempo_inicial_prontuario'])) {
    $_SESSION['tempo_inicial_prontuario'] = time();
}

// Recupera o prontuário do paciente selecionado
if (isset($_GET['id_consulta'])) {
  $idConsulta = $_GET['id_consulta'];

  // Salva o tempo inicial da consulta
  $_SESSION['tempo_inicial_consulta'] = time();

  $sql = "SELECT a.prontuario, p.nome_completo AS nome_paciente
          FROM id21615508_projetophp.agendamentos AS a
          INNER JOIN id21615508_projetophp.pacientes AS p ON a.paciente_id = p.id_paciente
          WHERE a.id = ? AND a.medico_id = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $idConsulta, $id_medico);

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result === false) {
      die('Erro na execução da consulta: ' . $stmt->error);
  }

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $prontuario_paciente = $row['prontuario'];
      $nome_paciente = $row['nome_paciente'];
  } else {
      // Redireciona se a consulta não pertencer ao médico logado
      header("Location: telamedico.php");
      exit();
  }

  $stmt->close();
} else {
  // Redireciona se o ID da consulta não estiver definido
  header("Location: telamedico.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prontuario_novo = $_POST['prontuario_novo'];

    // Calcula a duração do prontuário
if (isset($_SESSION['tempo_inicial_prontuario'])) {
    $tempo_inicial_prontuario = $_SESSION['tempo_inicial_prontuario'];
    $tempo_final_prontuario = time();
    $duracao_prontuario = $tempo_final_prontuario - $tempo_inicial_prontuario;

    // Obtém a data atual
    $data_modificacao = date('Y-m-d H:i:s');

    // Salva a duração e a data de modificação no banco de dados
    $updateSql = "UPDATE id21615508_projetophp.agendamentos SET prontuario = ?, tempo_consulta = tempo_consulta + ?, data_consulta = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sisi", $prontuario_novo, $duracao_prontuario, $data_modificacao, $idConsulta);
    $stmt->execute();

    unset($_SESSION['tempo_inicial_prontuario']); // Limpa a variável de tempo inicial após salvar
}


    // Recarrega a página após salvar o prontuário
    header("Location: medicoArea.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Prontuários</title>
    <link rel="stylesheet" href="./styles/histConsultStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="shortcut icon" href="./assets/dentinho.jpg" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cabin+Condensed&family=Inter&family=Mooli&display=swap" rel="stylesheet">
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Visualização de Prontuários</h1>
            <div class="columns">
                <div class="column is-one-third">
                    <div class="box">
                    <h2 class="subtitle">Paciente: <?php echo $nome_paciente; ?></h2>
                        <form method="post">
                            <p>Prontuário:</p>
                            <div class="content">
                            <textarea class='textarea' name='prontuario_novo' placeholder='Editar Prontuário do <?php echo $nome_paciente; ?>'><?php echo $prontuario_paciente; ?></textarea>
                            </div>
                            <button type="submit" class="button-bot">Salvar</button>
                            <a href="./medicoArea.php"><button type="submit" class="button-bot">Voltar</a></button> <!--inserir html para voltar-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>