<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article class="container">
    <header class="page-header">
        <h2><?= htmlspecialchars($title ?? 'Categorias') ?></h2>
        <a href="/categorias/criar" class="btn btn-primary">Criar nova Categoria</a>
    </header>

    <?php if (!empty($categorias)): ?>
        <ul class="category-list">
            <?php foreach ($categorias as $item): ?>
                <li class="category-item">
                    <a href="/categorias/ver?id=<?= $item['id'] ?>" class="category-name">
                        <?= htmlspecialchars($item['nome']) ?>
                    </a>
                    <div class="category-actions">
                        <a href="/categorias/editar?id=<?= $item['id'] ?>" class="btn btn-secondary outline">
                            Editar
                        </a>
                        <form 
                            action="/api/categorias/deletar" 
                            method="POST" 
                            class="delete-form">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                            <button type="submit" class="btn btn-danger outline">Excluir</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="no-results">Nenhuma categoria cadastrada.</p>
    <?php endif; ?>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

<style>
    .container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 1.5rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #ddd;
        padding-bottom: .5rem;
    }

    .btn {
        display: inline-block;
        padding: .4rem .8rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .btn-primary {
        background-color: #0078d7;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #005fa3;
    }

    .btn-secondary {
        background-color: #e0e0e0;
        color: #333;
    }

    .btn-secondary:hover {
        background-color: #cfcfcf;
    }

    .btn-danger {
        background-color: #f44336;
        color: white;
    }

    .btn-danger:hover {
        background-color: #d32f2f;
    }

    .outline {
        border: none;
    }

    .category-list {
        list-style: none;
        padding: 0;
    }

    .category-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafafa;
        padding: .75rem 1rem;
        margin-bottom: .5rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: background 0.2s;
    }

    .category-item:hover {
        background: #f0f8ff;
    }

    .category-name {
        font-weight: 500;
        color: #0078d7;
        text-decoration: none;
    }

    .category-name:hover {
        text-decoration: underline;
    }

    .category-actions {
        display: flex;
        gap: .5rem;
    }

    .delete-form {
        display: inline-block;
        margin: 0;
    }

    .no-results {
        text-align: center;
        color: #777;
        font-style: italic;
        margin-top: 2rem;
    }
</style>
