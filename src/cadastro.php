<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "crud_btz";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    if (empty($nome) || empty($descricao) || empty($preco) || empty($quantidade)) {
        $mensagem = "Preencha todos os campos!";
    } else {

        $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, quantidade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $nome, $descricao, $preco, $quantidade);

        if ($stmt->execute()) {
            $mensagem = "Produto cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar!";
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
    <link rel="stylesheet" href="css/cadastro.css">
    <link rel="shortcut icon" href="images/grupo-btz-log.png" type="image/x-icon">
<title>Cadastrar Produto - BTZ</title>

</head>

<body>

<nav class="navbar">
  <div class="logo">
    <img src="images/grupo-btz-log.png" alt="Logo BTZ">
  </div>
<div class="menu-toggle" onclick="toggleMenu()">☰</div>
  <ul class="nav-links">
    <li><a href="index.html">Início</a></li>
    <li><a href="cadastro.php">Cadastrar novos produtos</a></li>
    <li><a href="lista.php">Visualizar produtos</a></li>
    <li><a href="contato.php">Contato</a></li>
  </ul>
</nav>

<div class="container">
  <div class="form-box">

    <h2>Cadastrar Produto</h2>

    <?php if (!empty($mensagem)) echo "<p class='mensagem'>$mensagem</p>"; ?>

    <form method="POST">

      <label>Nome:</label>
      <input type="text" name="nome">

      <label>Descrição:</label>
      <textarea name="descricao"></textarea>

      <label>Preço:</label>
      <input type="number" step="0.01" name="preco">

      <label>Quantidade:</label>
      <input type="number" name="quantidade">

      <button type="submit">Cadastrar</button>

    </form>

  </div>
</div>

<script>
function toggleMenu() {
  document.querySelector('.nav-links').classList.toggle('active');
}
</script>
</body>
</html>