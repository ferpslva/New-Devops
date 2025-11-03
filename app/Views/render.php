<?php
    namespace App\Views;

    class Render {
        function render(
            string $view,
            array $params = []
        ) {
            // extrair todos os parametros
            // para criar variaveis
            extract($params);

            // start o cacha ob_start
            ob_start();

            // chamar aqui os arquivos de views
            require __DIR__ . "/$view.php";

            // colocar o conteudo da view dentro de uma
            // variavel
            $conteudo = ob_get_clean();

            ob_start();
            // renderizando a nossa master page
            require __DIR__ . "/layout.php";
            return ob_get_clean();
        }
    }
?>