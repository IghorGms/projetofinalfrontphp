<?php
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_POST["usuario_id"];

    // Atualize o estado de bloqueio no banco de dados para desbloquear (0)
    $sql = "UPDATE id21615508_projetophp.medicos SET bloqueado = 0 WHERE id_medico = $usuario_id";
    mysqli_query($conn, $sql);

    // Redirecione de volta para a página de gerenciamento de usuários
    header("Location: telaAdm.php");

    exit();
}
