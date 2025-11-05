<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
  <header class="grid">
    <h2><?= htmlspecialchars($title ?? 'Categorias') ?></h2>
    <a href="/categorias/criar" role="button">Criar nova Categoria</a>
  </header>

  <?php if (!empty($categorias)): ?>
    <ul>
      <?php foreach ($categorias as $item): ?>
        <li>
          <article class="grid">
            <div>
              <a href="/categorias/ver?id=<?= $item['id'] ?>">
                <strong><?= htmlspecialchars($item['nome']) ?></strong>
              </a>
            </div>

            <div class="grid" style="justify-content: end; gap: 0.5rem;">
              <a href="/categorias/editar?id=<?= $item['id'] ?>" role="button" class="secondary">
                Editar
              </a>
              <form action="/api/categorias/deletar" method="POST">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <button type="submit" class="contrast">Excluir</button>
              </form>
            </div>
          </article>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p><em>Nenhuma categoria cadastrada.</em></p>
  <?php endif; ?>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
