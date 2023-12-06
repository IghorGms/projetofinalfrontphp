<?php
    include "conexao.php";

    // Função para adicionar mensagens de erro à sessão
    function addError($message) {
        $_SESSION['errors'][] = "<span style='color: red;'>$message</span>";
    }

    if (isset($_POST['submit'])) {
        $nomeUsuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        // Iniciar a sessão e definir variáveis de sessão
        session_start();

        // Validar o nome de usuário
        if (empty($nomeUsuario)) {
            addError("Por favor, preencha o campo Nome de Usuário.");
        }

        // Validar a senha
        if (empty($senha)) {
            addError("Por favor, preencha o campo Senha.");
        }

        // Verificar se há erros na validação
        if (!empty($_SESSION['errors'])) {
            // Se houver erros, exibir as mensagens de erro no local apropriado no formulário
            echo "<div class='errors'>";
            foreach ($_SESSION['errors'] as $error) {
                echo $error . "<br>";
            }
            echo "</div>";
            unset($_SESSION['errors']); // Remover a variável 'errors' da sessão
        } else {
            // Consulta para verificar se o nome de usuário e a senha correspondem
            $sqlVerificaLoginMedico = "SELECT * FROM id21615508_projetophp.medicos WHERE nome_usuario = '$nomeUsuario' AND senha = '$senha' AND bloqueado = 0";
            $resultLoginMedico = $conn->query($sqlVerificaLoginMedico);

            $sqlVerificaLoginPaciente = "SELECT * FROM id21615508_projetophp.pacientes WHERE nome_usuario = '$nomeUsuario' AND senha = '$senha' AND  bloqueado = 0";
            $resultLoginPaciente = $conn->query($sqlVerificaLoginPaciente);
            
            if('bloqueado' == 1){
                echo "Usuario Bloqueado!";
                exit;
            }
            if ($nomeUsuario == 'ADMIN') {
                $_SESSION['tipo_usuario'] = 'ADMIN';
                header("Location:telaAdm.php");
                exit(); // Adicionado para evitar a execução de código adicional após o redirecionamento
            } elseif ($resultLoginMedico->num_rows > 0) {
                $rowMedico = $resultLoginMedico->fetch_assoc();
                $_SESSION['id_medico'] = $rowMedico['id_medico'];
                $_SESSION['nome_medico'] = $rowMedico['nomeSobrenome'];
                $_SESSION['tipo_usuario'] = 'medico';
                header("Location: medicoArea.php");
                exit(); // Adicionado para evitar a execução de código adicional após o redirecionamento
            } elseif ($resultLoginPaciente->num_rows > 0) {
                $rowPaciente = $resultLoginPaciente->fetch_assoc();
                $_SESSION['id_paciente'] = $rowPaciente['id_paciente'];
                $_SESSION['nome_paciente'] = $rowPaciente['nome_completo'];
                $_SESSION['tipo_usuario'] = 'paciente';
                header("Location: pacienteArea.php");
                exit(); // Adicionado para evitar a execução de código adicional após o redirecionamento
            } else {
                // Usuário não encontrado ou senha incorreta
                // Adicione aqui a lógica para lidar com tentativas de login mal-sucedidas
                echo ("Usuário ou senha incorretos ou seu usuario está bloqueado. Tente novamente.");
            }
        }
    }
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="shortcut icon" href="./assets/dentinho.jpg" type="image/x-icon" />
        <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap"
            rel="stylesheet"
            type='text/css'
        >
        <link rel="stylesheet" type="text/css" href="./styles/loginstyle.css">
    </head>
    <body>        
        <div class="form-wrapper">
            <div class="form-side">
                
                <form class="my-form" action="login.php" method="post">
                    <div class="form-welcome-row">
                        <h1> Bem Vindo de Volta!</h1>
                        </h1>
                    </div>
                    <div class="socials-row">
                        <a href="#" title="Use Google">
                            <img src="assets/google.png" alt="Google">Entrar com Gmail
                        </a>
                        <a href="#" title="Use Apple">
                            <img src="assets/apple.png" alt="Apple"> Apple ID
                        </a>
                    </div>
                    <div class="divider">
                        <div class="divider-line"></div> Ou <div class="divider-line"></div>
                    </div>
                    <div class="text-field">
                        <label for="email">Usuário
                            <input
                                type="text"
                                id="email"
                                name="usuario"
                                autocomplete="off"
                                placeholder="Digite seu usuário"
                                
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                <path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28"></path>
                            </svg>
                        </label>
                    </div>
                    <div class="text-field">
                        <label for="password">Senha:
                            <input
                                id="password"
                                type="password"
                                name="senha"
                                placeholder="Senha"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path>
                                <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path>
                                <path d="M8 11v-4a4 4 0 1 1 8 0v4"></path>
                            </svg>
                        </label>
                    </div>
                    <button class="my-form__button" name="submit" type="submit" >
                        Entrar
                    </button>
                    <div class="my-form__actions">
                        <a href="#" title="Create Account">
                            Esqueci minha senha
                        </a>
                        <a href="./index.html" title="Create Account">
                            Voltar ao inicio
                        </a>
                    </div>
                </form>
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
</html>