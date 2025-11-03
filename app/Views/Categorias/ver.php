<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
    <?php if(!$categoria): ?>
        <p>Categoria não encontrada.</p>
    <?php else: ?>
        <header>
            <h2>Categoria #<?= htmlspecialchars($categoria['id']) ?></h2>
        </header>
        <p><strong>Nome:</strong> <?= htmlspecialchars($categoria['nome']) ?></p>
    <?php endif; ?>

    <footer>
         <a href="/categorias" role="button" class="secondary">Voltar para a lista</a>
    </footer>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>