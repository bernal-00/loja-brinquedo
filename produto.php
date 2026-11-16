
<?php
require_once "config/conexao.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT produtos.*, categorias.nome AS categoria
        FROM produtos
        LEFT JOIN categorias ON produtos.id_categoria = categorias.id
        WHERE produtos.id = $id";

$resultado = $conexao->query($sql);

if (!$resultado || $resultado->num_rows == 0) {
    echo "Produto não encontrado!";
    exit;
}

$produto = $resultado->fetch_assoc();
$imagemProduto = $produto['imagem'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - Toy Kids</title>
    <link rel="stylesheet" href="css/style.css">

    <script src="https://kit.fontawesome.com/65f22fe718.js"
        crossorigin="anonymous"></script>
</head>

<body>

<header class="topo-produto">

    <div class="logo">
        <h1>Toy Kids</h1>
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="index.php#produtos">Ver produtos</a>
        <a href="contato.php">Contato</a>
        <a href="sobre.php">Sobre nós</a>
    </nav>

    <div class="icones-topo">
        <a href="login.php" title="Minha conta">
            <i class="fa-solid fa-user"></i>
        </a>

        <a href="carrinho.php" title="Carrinho">
            <i class="fa-solid fa-cart-shopping"></i>
        </a>
    </div>

</header>

<main class="produto-container">

    <div class="galeria-produto">

        <div class="miniaturas">
            <div class="miniatura ativa">
                <img
                    src="imagens/<?php echo htmlspecialchars($imagemProduto); ?>"
                    alt="Imagem do produto">
            </div>
        </div>

        <div class="imagem-principal">
            <img
                src="imagens/<?php echo htmlspecialchars($imagemProduto); ?>"
                alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                onerror="this.alt='Imagem não encontrada'">
        </div>

    </div>

    <section class="informacoes-produto">

        <span class="estoque">
            <?php
            echo ((int)$produto['estoque'] > 0)
                ? 'Em estoque'
                : 'Esgotado';
            ?>
        </span>

        <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>

        <div class="avaliacao">
            <span class="estrelas">★★★★★</span>
            <span class="nota">5.0</span>
        </div>

        <h3 class="preco">
            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
        </h3>

        <p class="descricao">
            <?php echo nl2br(htmlspecialchars($produto['descricao'])); ?>
        </p>

        <div class="detalhes">

            <p>
                <strong>Marca:</strong>
                <?php echo htmlspecialchars($produto['marca']); ?>
            </p>

            <p>
                <strong>Categoria:</strong>
                <?php echo htmlspecialchars($produto['categoria'] ?? ''); ?>
            </p>

            <p>
                <strong>Idade:</strong>
                <?php echo htmlspecialchars($produto['idade']); ?>
            </p>

            <p>
                <strong>Estoque:</strong>
                <?php echo (int)$produto['estoque']; ?> unidades
            </p>

        </div>

        <div class="quantidade">

            <button type="button" onclick="alterarQuantidade(-1)">
                −
            </button>

            <span id="quantidade">1</span>

            <button type="button" onclick="alterarQuantidade(1)">
                +
            </button>

        </div>

        <button
            class="btn-adicionar"
            type="button"
            onclick="adicionarCarrinho()"
            <?php echo ((int)$produto['estoque'] <= 0) ? 'disabled' : ''; ?>
        >
            <i class="fa-solid fa-cart-shopping"></i>
            Adicionar ao Carrinho
        </button>

        <a href="carrinho.php" class="btn-comprar">
            Compre Agora
        </a>

    </section>

</main>

<div id="mensagem-carrinho"></div>

<script>
let quantidade = 1;

const estoque = <?php echo (int)$produto['estoque']; ?>;

function alterarQuantidade(valor) {

    if (estoque <= 0) {
        return;
    }

    quantidade += valor;

    if (quantidade < 1) {
        quantidade = 1;
    }

    if (quantidade > estoque) {
        quantidade = estoque;
    }

    document.getElementById("quantidade").textContent = quantidade;
}

function adicionarCarrinho() {

    if (estoque <= 0) {
        return;
    }

    const mensagem = document.getElementById("mensagem-carrinho");

    mensagem.textContent = "✓ Produto adicionado ao carrinho!";
    mensagem.style.display = "block";

    clearTimeout(window.temporizadorMensagem);

    window.temporizadorMensagem = setTimeout(function() {
        mensagem.style.display = "none";
    }, 3000);
}
</script>

</body>
</html>
