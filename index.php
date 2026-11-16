
<?php
require_once "config/conexao.php";

$sql = "SELECT produtos.*, categorias.nome AS categoria
        FROM produtos
        LEFT JOIN categorias
        ON produtos.id_categoria = categorias.id
        ORDER BY produtos.id DESC";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Kids | Loja de Brinquedos</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="header">
    <div class="container">

        <a href="index.php" class="logo">Toy Kids</a>

        <nav class="menu">
            <a href="index.php">Início</a>
            <a href="#categorias">Categorias</a>
            <a href="#produtos">Produtos</a>
            <a href="#contato">Contato</a>
        </nav>

    </div>
</header>

<main>

    <section class="banner">
        <div class="container banner-content">

            <div>
               <img src="banner.jpg" alt="">
            </div>

        </div>
    </section>

    <section class="categorias container" id="categorias">

        <h2>Explore nossas categorias</h2>

        <div class="categorias-lista">

            <?php
            $categorias = $conexao->query(
                "SELECT * FROM categorias ORDER BY nome ASC"
            );

            if ($categorias):
                while ($categoria = $categorias->fetch_assoc()):
            ?>

                <a href="#produtos" class="categoria">
                    <?php echo htmlspecialchars($categoria['nome']); ?>
                </a>

            <?php
                endwhile;
            endif;
            ?>

        </div>

    </section>

    <section class="produtos container" id="produtos">

        <h2>Nossos brinquedos</h2>

        <p class="subtitulo">
            Confira nossas opções e escolha sua próxima diversão!
        </p>

        <div class="produtos-lista">

            <?php if ($resultado && $resultado->num_rows > 0): ?>

                <?php while ($produto = $resultado->fetch_assoc()): ?>

                    <article class="card-produto">

                        <div class="imagem-produto">

                            <?php if (!empty($produto['imagem'])): ?>

                                <img
                                    src="imagens/<?php echo htmlspecialchars($produto['imagem']); ?>"
                                    alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                    onerror="this.style.display='none'"
                                >

                            <?php else: ?>

                                <span>Imagem indisponível</span>

                            <?php endif; ?>

                        </div>

                        <div class="info-produto">

                            <span class="categoria-produto">
                                <?php echo htmlspecialchars($produto['categoria'] ?? 'Brinquedo'); ?>
                            </span>

                            <h3>
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </h3>

                            <p class="marca">
                                Marca:
                                <?php echo htmlspecialchars($produto['marca'] ?? 'Não informada'); ?>
                            </p>

                            <p class="preco">
                                R$
                                <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </p>

                            <div class="botoes-produto">

                                <a
                                    href="produto.php?id=<?php echo (int)$produto['id']; ?>"
                                    class="btn-produto"
                                >
                                    Ver detalhes
                                </a>

                                <button
                                    type="button"
                                    class="btn-carrinho"
                                    onclick="adicionarCarrinho(this)"
                                >
                                    Adicionar ao carrinho
                                </button>

                            </div>

                        </div>

                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <p>Nenhum produto cadastrado no momento.</p>

            <?php endif; ?>

        </div>

    </section>

</main>

<footer class="footer" id="contato">

    <div class="container">

        <h2>Toy Kids</h2>

        <p>A diversão está em cada brincadeira!</p>

        <p>&copy; 2026 Toy Kids - Todos os direitos reservados.</p>

    </div>

</footer>

<div id="mensagem-carrinho"></div>

<script>
function adicionarCarrinho(botao) {

    const mensagem = document.getElementById("mensagem-carrinho");

    const card = botao.closest(".card-produto");

    const nome = card.querySelector("h3").textContent.trim();

    mensagem.textContent = "✓ " + nome + " adicionado ao carrinho!";
    mensagem.style.display = "block";

    clearTimeout(window.temporizadorMensagem);

    window.temporizadorMensagem = setTimeout(function() {
        mensagem.style.display = "none";
    }, 3000);
}
</script>

</body>
</html>
