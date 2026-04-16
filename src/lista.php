<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "crud_btz";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $conn->query("DELETE FROM produtos WHERE id=$id");
    header("Location: lista.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, quantidade=? WHERE id=?");
    $stmt->bind_param("ssdii", $nome, $descricao, $preco, $quantidade, $id);
    $stmt->execute();
}

$result = $conn->query("SELECT * FROM produtos ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="css/lista.css">
    <link rel="shortcut icon" href="images/grupo-btz-log.png" type="image/x-icon">
<title>Lista de produtos - BTZ</title>

</head>

<body>

<nav class="navbar">
  <div class="logo">
    <img src="images/grupo-btz-log.png">
  </div>
<div class="menu-toggle" onclick="toggleMenu()">☰</div>
  <ul class="nav-links">
    <li><a href="index.html">Início</a></li>
    <li><a href="cadastro.php">Cadastrar</a></li>
    <li><a href="contato.php">contato</a></li>
  </ul>
</nav>

<div class="container">

<h2>Lista de Produtos</h2>

<table>
<tr>
  <th>ID</th>
  <th>Nome</th>
  <th>Descrição</th>
  <th>Preço</th>
  <th>Qtd</th>
  <th>Ações</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['nome'] ?></td>
  <td><?= $row['descricao'] ?></td>
  <td>R$ <?= $row['preco'] ?></td>
  <td><?= $row['quantidade'] ?></td>
  <td>
    <button class="btn editar"
      onclick="abrirModal(
        '<?= $row['id'] ?>',
        '<?= $row['nome'] ?>',
        '<?= $row['descricao'] ?>',
        '<?= $row['preco'] ?>',
        '<?= $row['quantidade'] ?>'
      )">Editar</button>

    <button class="btn excluir" onclick="confirmarExclusao(<?= $row['id'] ?>)">
         Excluir
    </button>
    </a>
  </td>
</tr>
<?php endwhile; ?>

</table>
</div>

<div class="modal" id="modal">
  <div class="modal-content">

    <h3>Editar Produto</h3>

    <form method="POST">
      <input type="hidden" name="id" id="id">

      <input type="text" name="nome" id="nome" placeholder="Nome">
      <textarea name="descricao" id="descricao"></textarea>
      <input type="number" step="0.01" name="preco" id="preco">
      <input type="number" name="quantidade" id="quantidade">

      <button type="submit" name="editar">Salvar</button>
    </form>

    <br>
    <button onclick="fecharModal()">Cancelar</button>

  </div>
</div>
<div class="modal" id="modalDelete">
  <div class="modal-content">

    <h3>Tem certeza?</h3>

    <p style="text-align:center; margin-bottom:20px;">
      Esses dados serão excluídos permanentemente.
    </p>

    <div class="modal-actions">
      <button id="btnConfirmar" class="btn-excluir">Excluir</button>
      <button onclick="fecharModalDelete()" class="btn-cancelar">Cancelar</button>
    </div>

  </div>
</div>

<script>
function abrirModal(id, nome, descricao, preco, quantidade) {
  document.getElementById("modal").style.display = "flex";

  document.getElementById("id").value = id;
  document.getElementById("nome").value = nome;
  document.getElementById("descricao").value = descricao;
  document.getElementById("preco").value = preco;
  document.getElementById("quantidade").value = quantidade;
}

function fecharModal() {
  document.getElementById("modal").style.display = "none";
}
let idExcluir = null;

function confirmarExclusao(id) {
  idExcluir = id;
  document.getElementById("modalDelete").style.display = "flex";
}

function fecharModalDelete() {
  document.getElementById("modalDelete").style.display = "none";
}

document.getElementById("btnConfirmar").onclick = function () {
  window.location.href = "?excluir=" + idExcluir;
};

function toggleMenu() {
  document.querySelector('.nav-links').classList.toggle('active');
}
</script>
</body>
</html>