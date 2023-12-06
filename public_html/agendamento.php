<?php
// Obter dados do formulário
session_start();
include "conexao.php";

$horario = $_POST['horario'];
$idPaciente = $_SESSION['id_paciente'];
$id_medico = $_POST['id_medico'];

// Verificar se o horário está disponível
$sql = "SELECT id FROM id21615508_projetophp.agendamentos WHERE horario = '$horario' AND medico_id = $id_medico AND disponivel != 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Horário já agendado, faça o tratamento adequado (ex: exiba uma mensagem de erro)
    $message = "Horário já agendado. Escolha outro horário.";
} else {
    // Horário disponível, agendar
    $sql = "INSERT INTO id21615508_projetophp.agendamentos (medico_id, paciente_id, horario, disponivel) VALUES ($id_medico, $idPaciente, '$horario', 0 )";

    if ($conn->query($sql) === TRUE) {
        $message = "Agendamento realizado com sucesso!";
    } else {
        $message = "Erro ao agendar: " . $conn->error;
    }
}

// Redirecionar de volta à página do médico com a mensagem
header("Location: pacienteArea.php?message=" . urlencode($message));
exit();
?>
