<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h2><?= htmlspecialchars($title) ?></h2>
        <a href="/produtos/criar" role="button">Novo Produto</a>
    </header>

    <ul>
        <?php foreach($produtos as $item): ?>
            <li style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                
                <a href="/produtos/exibir?id=<?= $item['id'] ?>">
                    <?= htmlspecialchars($item['nome']) ?>
                </a>
                
                <div role="group">
                    <a href="/produtos/editar?id=<?= $item['id'] ?>" role="button" class="secondary outline">
                        Editar
                    </a>

                    <form 
                        action="/api/produtos/deletar" 
                        method="POST" 
                        style="display: inline-block; margin-bottom: 0;">
                        
                        <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                        <button type="submit" class="contrast outline">Excluir</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>