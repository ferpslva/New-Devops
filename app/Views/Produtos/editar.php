<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
    <header>
        <h1><?= htmlspecialchars($title) ?></h1>
    </header>
    
    <form action="/produtos/update" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($produto['id']) ?>">

        <div>
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        </div>

        <div>
            <label for="categoria_id">Categoria:</label>
            <select name="categoria_id" id="categoria_id" required>
                <option value="">Selecione...</option>
                <?php foreach ($categorias as $cat): ?>
                    <option 
                        value="<?= $cat['id'] ?>" 
                        <?= ($cat['id'] == $produto['categoria_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <footer style="display: flex; justify-content: space-between; align-items: center;">
             <a href="/produtos" role="button" class="secondary">Cancelar</a>
            <button type="submit">Salvar Alterações</button>
        </footer>
    </form>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>