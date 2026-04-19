<?php
class BaseController
{
    protected array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/config.php';
    }


   protected function render(string $view, array $data = [], ?string $layout = 'app'): void
   {
   

    extract($data);
    if (!isset($this->config)) {
        $this->config = require __DIR__ . '/../config/config.php';
          
    }

    $config = $this->config;

    $viewFile = __DIR__ . '/../views/' . $view . '.php';

    if (!file_exists($viewFile)) {
        http_response_code(404);
        echo 'View not found';
        return;
    }

    
    if ($layout === null) {
       
        include $viewFile;
        return;
    }

    $layoutPath = __DIR__ . '/../views/layouts/' . $layout . '.php';

    if (!file_exists($layoutPath)) {
        http_response_code(500);
        echo 'Layout not found';
        return;
    }

    include $layoutPath;
}

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
