<?php
// chamando o arquivo config do composer
require_once __DIR__ . '/vendor/autoload.php';

// chamando as classes de controllers
use App\Controllers\CategoriaController;
use App\Controllers\ProdutoController;

// --- INÍCIO DA MUDANÇA ---

// pega URL crua (sem tratamento)
$uri = $_SERVER["REQUEST_URI"] ?? "/";

// tratamento QUERY STRING (ex: ?parametro=valor)
$uri = strtok($uri, "?");

// remover a barra final
$uri = rtrim($uri, '/');

// Se a URI estiver vazia (era a raiz '/'), defina como '/'
if (empty($uri)) {
    $uri = '/';
}

// --- FIM DA MUDANÇA ---


// pegar o método da requisição
$metodo = $_SERVER['REQUEST_METHOD'];

// criação das rotas de views
if ($metodo == 'GET') {
    // Rota Raiz (Exemplo, caso você tenha uma)
    if ($uri === '/') {
        // Você pode colocar um "Bem-vindo" ou redirecionar
        // Ex: header('Location: /categorias');
        echo "Bem-vindo ao sistema MVC!";
        exit;
    }

    // Rotas de Categorias
    if ($uri === '/categorias') {
        echo (new CategoriaController())->index();
        exit;
    }
    if ($uri === '/categorias/criar') {
        echo (new CategoriaController())->criar();
        exit;
    }
    if ($uri === '/categorias/ver') {
        $id = (int)($_GET['id'] ?? 0);
        echo (new CategoriaController())->ver($id);
        exit;
    }
    if ($uri === '/categorias/editar') {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            echo (new CategoriaController())->editar($id);
        } else {
            echo "ID da categoria não informado!";
        }
    exit;
    }

    // Rotas de Produtos
    if ($uri === '/produtos') {
        echo (new ProdutoController())->index();
        exit;
    }
    if ($uri === '/produtos/criar') {
        echo (new ProdutoController())->criar();
        exit;
    }
    if ($uri === '/produtos/exibir') {
        $id = (int)($_GET['id'] ?? 0);
        echo (new ProdutoController())->exibir($id);
        exit;
    }
    if ($uri === '/produtos/editar') {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            echo (new ProdutoController())->editar($id);
        } else {
            echo "ID do produto não informado!";
        }
        exit;
    }
    
}

// criação das rotas de APIs
// API de Categorias
if ($uri === '/api/categorias' && $metodo == 'GET') {
    echo (new CategoriaController())->list();
    exit;
}
if ($uri === '/api/categorias' && $metodo == 'POST') {
    (new CategoriaController())->create();
    
    // --- CORREÇÃO DE REDIRECT ---
    header('Location: /categorias');
    exit;

}
if ($uri === '/categorias/update' && $metodo == 'POST') {
    (new CategoriaController())->update();
    exit;
}
if ($uri === '/api/categorias/deletar' && $metodo == 'POST') {
    $id = (int)($_POST['id']);
    (new CategoriaController())->delete();
    
    // --- CORREÇÃO DE REDIRECT ---
    header('Location: /categorias');
    exit;

}

// API de Produtos
if ($uri === '/api/produtos' && $metodo == 'GET') {
    echo (new ProdutoController())->list();
    exit;
}
if ($uri === '/api/produtos' && $metodo == 'POST') {
    (new ProdutoController())->create();
    
    // --- CORREÇÃO DE REDIRECT ---
    header('Location: /produtos');
    exit;
    
}
if ($uri === '/produtos/update' && $metodo == 'POST') {
    (new ProdutoController())->update();
    exit;
}
if ($uri === '/api/produtos/deletar' && $metodo == 'POST') {
    $id = (int)($_POST['id']);
    (new ProdutoController())->delete();
    
    // --- CORREÇÃO DE REDIRECT ---
    header('Location: /produtos');
    exit;

}

// Se nenhuma rota for encontrada, mostre um 404
http_response_code(404);
echo "Página não encontrada (404)";
exit;

?>