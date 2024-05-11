<?php

namespace App\Core;

use App\Helpers\Log;
use Exception;

class ViewRenderer {
    protected static $layout;
    protected static $view;
    protected static $data = [];

    /**
     * Return static layout.
     *
     * @param $layout
     * @return self
     */
    public static function layout($layout = null)
    {
        self::$layout = $layout;
        return new self();
    }

    /**
     * Return view instance by static var.
     *
     * @param $view
     * @return $this
     */
    public function view($view)
    {
        self::$view = $view;
        return $this;
    }

    /**
     * Attach data to view
     *
     * @param $data
     * @return $this
     */
    public function withData($data = [])
    {
        self::$data = [
            ...[
                'title' => 'Default title',
            ],
            ...$data
        ];
        return $this;
    }

    /**
     * Convert templates keys to filepath.
     *
     * @param string $templateKey
     * @return string
     */
    private function parseTemplate(string $templateKey): string
    {
        return __DIR__ . '/../Views/' . str_replace('.', '/', $templateKey) . '.php';
    }

    /**
     * Main render.
     *
     * @return void
     * @throws Exception
     */
    public function render()
    {
        $layoutFile = $this->parseTemplate(self::$layout) ?? '';
        $viewFile = $this->parseTemplate(self::$view);

        if (file_exists($viewFile)) {
            ob_start();
            extract(self::$data);
            require $viewFile;
            $content = ob_get_clean();

            if (file_exists($layoutFile)) {
                require $layoutFile;
            } else {
                echo $content;
            }
        } else {
            throw new Exception("View file not found.");
        }
    }
}
