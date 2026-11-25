<?php

namespace App\Presentation;

class ViewRenderer{
    private string $viewsPath;
    public function __construct(string $viewsPath){
        $this->viewsPath = rtrim($viewsPath, "/");
    }
    public function render(string $template, array $data = []): string{
        $filePath = $this->viewsPath . '/' . ltrim($template, '/') . '.php';

        if (!file_exists($filePath)) {
            throw new \RuntimeException("Шаблон не найден: {$filePath}");
        }

        extract($data);

        ob_start();
        require $filePath;
        return ob_get_clean();
    }
}