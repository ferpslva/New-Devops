<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h2><?= htmlspecialchars($title ?? 'Categorias') ?></h2>
        <a href="/categorias/criar" role="button">Criar nova Categoria</a>
    </header>

    <ul>
        <?php foreach($categorias as $item): ?>
            <li style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                
                <a href="/categorias/ver?id=<?= $item['id'] ?>">
                    <?= htmlspecialchars($item['nome']) ?>
                </a>
                
                <div role="group">
                    <a href="/categorias/editar?id=<?= $item['id'] ?>" role="button" class="secondary outline">
                        Editar
                    </a>

                    <form 
                        action="/api/categorias/deletar" 
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