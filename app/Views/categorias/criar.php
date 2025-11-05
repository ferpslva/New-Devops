<?php require_once __DIR__ . '/../layout/header.php'; ?>

<article>
  <header>
    <h2><?= htmlspecialchars($title ?? 'Nova Categoria') ?></h2>
  </header>

  <form method="POST" action="/api/categorias">
    <label for="nome">Nome da Categoria:</label>
    <input type="text" id="nome" name="nome" required />

    <footer class="grid">
      <a href="/categorias" role="button" class="secondary">Voltar</a>
      <button type="submit">Salvar</button>
    </footer>
  </form>
</article>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
