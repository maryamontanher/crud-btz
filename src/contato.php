<?php
require_once('contato/PHPMailer.php');
require_once('contato/SMTP.php');
require_once('contato/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensagemStatus = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $mensagem = $_POST["mensagem"];

    if (empty($nome) || empty($email) || empty($telefone) || empty($mensagem)) {
        $mensagemStatus = "Preencha todos os campos!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagemStatus = "E-mail inválido!";
    } else {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'emarya091@gmail.com';
            $mail->Password = 'kwqj hrbh bqgz kooo'; //  ideal usar variável de ambiente
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->setFrom('emarya091@gmail.com', 'Formulário BTZ');
            $mail->addReplyTo($email, $nome);
            $mail->addAddress('emarya091@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'Nova mensagem de contato';

            $mail->Body = "
                <h3>Nova mensagem recebida</h3>
                <strong>Nome:</strong> $nome<br>
                <strong>Email:</strong> $email<br>
                <strong>Telefone:</strong> $telefone<br><br>
                <strong>Mensagem:</strong><br>$mensagem
            ";

            $mail->AltBody = "Nome: $nome\nEmail: $email\nTelefone: $telefone\n\nMensagem:\n$mensagem";

            if ($mail->send()) {
                $mensagemStatus = "Mensagem enviada com sucesso!";
            } else {
                $mensagemStatus = "Erro ao enviar mensagem.";
            }

        } catch (Exception $e) {
            $mensagemStatus = "Erro: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="css/contato.css">
    <link rel="shortcut icon" href="images/grupo-btz-log.png" type="image/x-icon">
<title>Contato - BTZ</title>

</head>

<body>

<nav class="navbar">
  <div class="logo">
    <img src="images/grupo-btz-log.png" alt="Logo">
  </div>
<div class="menu-toggle" onclick="toggleMenu()">☰</div>
  <ul class="nav-links">
    <li><a href="index.html">Início</a></li>
    <li><a href="cadastro.php">Cadastrar</a></li>
    <li><a href="lista.php">Produtos</a></li>
    <li><a href="contato.php">Contato</a></li>
  </ul>
</nav>

<!-- CONTEÚDO -->
<div class="container">
  <div class="form-box">

    <h2>Fale Conosco</h2>

    <?php if (!empty($mensagemStatus)) echo "<p class='mensagem'>$mensagemStatus</p>"; ?>

    <form method="POST">

      <label>Nome:</label>
      <input type="text" name="nome">

      <label>Email:</label>
      <input type="email" name="email">

      <label>Telefone:</label>
      <input type="text" name="telefone">

      <label>Mensagem:</label>
      <textarea name="mensagem"></textarea>

      <button type="submit">Enviar</button>

    </form>

  </div>
</div>

<footer>
  Grupo BTZ &copy; <?php echo date("Y"); ?> - Todos os direitos reservados
</footer>
<script>
function toggleMenu() {
  document.querySelector('.nav-links').classList.toggle('active');
}
</script>
</body>
</html>