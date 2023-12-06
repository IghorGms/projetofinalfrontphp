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
$nome_medico = $_SESSION['nome_medico']; // Supondo que você já tenha essa informação


include "conexao.php";

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela do Médico</title>
    <link rel="stylesheet" href="./styles/areaMedStyle.css">
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

    <div class="head">
    <h class="head__call">Olá Dr. <?php echo $nome_medico; ?></h>
    </div>

    <section class="section">
        <div class="container">
            <h1 class="title">Pacientes e Prontuários</h1>
            <div class="container">
                <div class="row">
                    <?php
                    include "conexao.php";

                    $sql = "SELECT c.id, c.horario, c.data_consulta, p.nome_completo AS nome_paciente, m.nomeSobrenome AS nome_medico, c.prontuario, c.tempo_consulta
                    FROM id21615508_projetophp.agendamentos AS c
                    INNER JOIN id21615508_projetophp.pacientes AS p ON c.paciente_id = p.id_paciente
                    INNER JOIN id21615508_projetophp.medicos AS m ON c.medico_id = m.id_medico where medico_id = $id_medico";
                    
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='box'>";
                            echo "<h2 class='title'>Consulta #" . $row['id'] . ": " . $row['nome_paciente'] . "</h2>";
                            echo "<p>Horário: " . $row['horario'] . "</p>";
                            echo "<p>Data: " . $row['data_consulta'] . "</p>";
                            
                            // Exibir a duração da consulta
                            echo "<p>Duração Prontuário: " . gmdate("H:i:s", $row['tempo_consulta']) . "</p>";
                    
                            echo "<p>Atendido pelo Dr. " . $row['nome_medico'] . "</p>";
                            echo "<p>Prontuário:</p>";
                            echo "<div class='content' name='prontuario'>";
                            echo "<p>". $row['prontuario'] ."<p>";
                            echo "<button onclick='prontuariosalvo(\"prontuario.php?id_consulta=" . $row['id'] . "\")'>Editar prontuário </button>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Não há consultas agendadas.</p>";
                    }
                    
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </section>

     <script> 
        function prontuariosalvo(url) {
            window.location.href = url;
        }
    </script>
    <script src="../scripts/script.js"> </script>

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

</body>

<script src="./scripts/script.js"></script>

</html>