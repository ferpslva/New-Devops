<?php
    namespace App\Controllers;

    use App\Services\CategoriaService;
    use App\Views\Render;

    class CategoriaController {
        private CategoriaService $service;

        public function __construct() {
            $this->service = new CategoriaService();
        }

        public function index(): string {
            $title = "Categorias";
            $categorias = $this->service->list();
            return (new Render())->render('categorias/index', compact('title', 'categorias'));
        }
        
        // --- NOVO MÉTODO PARA A API ---
        public function list(): string {
            $categorias = $this->service->list();
            header('Content-Type: application/json');
            return json_encode($categorias);
        }

        public function criar(): string {
            $title = "Nova Categoria";
            return (new Render())->render('categorias/criar', compact('title'));
        }

        public function create() {
            if (!empty($_POST['nome'])) {
                $this->service->create($_POST['nome']);
            }
            // --- CORREÇÃO DE REDIRECT ---
            header('Location: /categorias');
            exit;
        }

        public function editar(int $id): string {
            $title = "Editar Categoria";
            $categoria = $this->service->findById($id);
            return (new Render())->render('categorias/editar', compact('title', 'categoria'));
        }

        public function update() {
            if (!empty($_POST['id']) && !empty($_POST['nome'])) {
                $this->service->update((int)$_POST['id'], $_POST['nome']);
            }
            // --- CORREÇÃO DE REDIRECT ---
            header('Location: /categorias');
            exit;
        }


        public function delete() {
            if (!empty($_POST['id'])) {
                $this->service->delete((int)$_POST['id']);
            }
            // --- CORREÇÃO DE REDIRECT ---
            header('Location: /categorias');
            exit;
        }
        
    }
?>