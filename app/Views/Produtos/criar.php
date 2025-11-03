<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
    <header>
        <h2><?= htmlspecialchars($title) ?></h2>
    </header>

    <form method="POST" action="/api/produtos">
        
        <label for="nome">Nome do Produto:</label>
        <input type="text" name="nome" id="nome" required />

        <label for="categoria_id">Categoria:</label>
        <select name="categoria_id" id="categoria_id" required>
            <option value="">Escolha uma categoria</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        
        <footer style="display: flex; justify-content: space-between; align-items: center;">
            <a href="/produtos" role="button" class="secondary">Voltar</a>
            <button type="submit">Salvar</button>
        </footer>
    </form>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>