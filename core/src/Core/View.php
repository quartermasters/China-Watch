<?php
declare(strict_types=1);

namespace RedPulse\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);

        // Hostinger Paths: views are in ../core/views/
        $viewFile = ROOT_DIR . '/core/views/' . $view . '.php';
        $layoutFile = ROOT_DIR . '/core/views/layout.php';

        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            // If it's an HTMX partial request, don't load layout
            if (isset($_SERVER['HTTP_HX_REQUEST'])) {
                echo $content;
            } else {
                // Wrap in master layout
                require $layoutFile;
            }
        } else {
            echo "View path not found: " . $viewFile;
        }
    }
}
