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

// Capturar os dados do formulário
if(isset($_POST['submit'])){
    // Recuperar os dados do formulário
    $nomesobrenome = $_POST['nomesobrenome'];
    $email = $_POST['email'];
    $localtrabalho = $_POST['localtrabalho'];
    $telefone = $_POST['numero'];
    $crm = $_POST['crm'];
    $especializacao = $_POST['especializacao'];
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Validar o nome de usuário
    if (empty($usuario)) {
        addError("Por favor, preencha o campo Nome de Usuário.");
    } else {
        // Verificar a conexão
        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        $sqlVerificaUsuarioMedico = "SELECT * FROM id21615508_projetophp.medicos WHERE nome_usuario = '$usuario'";
        $resultUsuarioMedico = $conn->query($sqlVerificaUsuarioMedico);
        
        // Verificar se o nome de usuário já existe em pacientes
        $sqlVerificaUsuarioPaciente = "SELECT * FROM id21615508_projetophp.pacientes WHERE nome_usuario = '$usuario'";
        $resultUsuarioPaciente = $conn->query($sqlVerificaUsuarioPaciente);
        
        // Verificar se o nome de usuário já existe em ambas as tabelas
        if ($resultUsuarioMedico->num_rows > 0) {
            addError("Nome de usuário já existe. Escolha outro.");
        } elseif ($resultUsuarioPaciente->num_rows > 0) {
            addError("Nome de usuário já existe. Escolha outro.");
        } 
    }

    // Validar o nome e sobrenome
    if (empty($nomesobrenome)) {
        addError("Por favor, preencha o campo Nome e Sobrenome.");
    }


    // Validar o email
    if (empty($email)) {
        addError("Por favor, preencha o campo Email.");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        addError("Formato de email inválido.");
    }

    if (empty($telefone)) {
        addError("Por favor, preencha o campo Número de telefone");
    }

    // Validar o local de trabalho
    if (empty($localtrabalho)) {
        addError("Por favor, preencha o campo Local de Trabalho.");
    }

    // Validar o CRM
    if (empty($crm)) {
        addError("Por favor, preencha o campo CRM (Registro Médico).");
    }

    // Validar a especialização
    if (empty($especializacao)) {
        addError("Por favor, selecione uma Especialização.");
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
        // Inserir os dados no banco de dados
        $sqlInserirMedico = "INSERT INTO id21615508_projetophp.medicos (nomeSobrenome, email, numero_telefone, endereco_de_trabalho, crm, nome_usuario, senha, especialidade)
                            VALUES ('$nomesobrenome', '$email','$telefone','$localtrabalho', '$crm', '$usuario', '$senha', '$especializacao')";


  if ($conn->query($sqlInserirMedico) === TRUE) {
    $_SESSION['MedicoInsert'] = "<span style='color: green;'>Cadastrado com sucesso!</span>";
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
                <h2>Cadastro Medico</h2>
                <?php
            if (isset($_SESSION['MedicoInsert'])) {
            echo $_SESSION['MedicoInsert'];
            unset($_SESSION['MedicoInsert']); // Limpar a mensagem após exibir
              }
            ?>
                <!-- Adicione o formulário -->
                <form action="cadastroMedico.php" method="post">
                    <!-- Campos do formulário -->
                    <input type="text" name="nomesobrenome" placeholder="Nome e Sobrenome" ><br>
                    <input type="email" name="email" placeholder="Email" ><br>
                    <input type="text" name="numero" placeholder="Número de telefone" ><br>
                    <input type="text" name="localtrabalho" placeholder="Local de Trabalho" ><br>
                    <input type="text" name="crm" placeholder="CRO" ><br>
                    <input type="text" name="usuario" placeholder="Nome de Usuario" ><br>
                    <input type="password" name="senha" placeholder="Senha" ><br>
                    <label for="especialidade">Especialidade</label><br>
                    <select id="especialidade" name="especializacao" class="input-global" required>
                            <option value="Bucomaxilofaciais">Bucomaxilofaciais</option>
                            <option value="Protese">Protese</option>
                            <option value="Disfunção Temporomandibular e Dor Orofacial">Disfunção Temporomandibular e Dor Orofacial</option>
                            <option value="Endodontia">Endodontia</option>                        
                            <option value="Estomato">Estomato</option>                        
                            <option value="Harmonização">Harmonização</option>                        
                            <option value="Homeopatia">Homeopatia</option>                        
                            <option value="Implantodontia">Implantodontia</option>                        
                            <option value="Ortodontia">Ortodontia</option>   
                        </optgroup>                         </select><br>
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