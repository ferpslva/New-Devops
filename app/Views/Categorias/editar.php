<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
    <header>
        <h1><?= htmlspecialchars($title) ?></h1>
    </header>

    <form action="/categorias/update" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($categoria['id']) ?>">

        <div>
            <label for="nome">Nome da Categoria:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($categoria['nome']) ?>" required>
        </div>

        <footer style="display: flex; justify-content: space-between; align-items: center;">
            <a href="/categorias" role="button" class="secondary">Cancelar</a>
            <button type="submit">Salvar Alterações</button>
        </footer>
    </form>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>