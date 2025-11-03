<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
    <?php if(!$produto): ?>
        <p>Produto não encontrado.</p>
    <?php else: ?>
        <header>
            <h2>Produto #<?= htmlspecialchars($produto['id']) ?></h2>
        </header>
        <p><strong>Nome:</strong> <?= htmlspecialchars($produto['nome']) ?></p>
        <p><strong>Categoria:</strong> <?= htmlspecialchars($produto['categoria_nome']) ?></p>
    <?php endif; ?>

    <footer>
         <a href="/produtos" role="button" class="secondary">Voltar para a lista</a>
    </footer>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>