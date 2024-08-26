<?php

namespace App\Core;

use App\Helpers\Log;
use Exception;

class ViewRenderer
{

    /**
     * @var string $layout
     */
    protected static string $layout;

    /**
     * @var string $view
     */
    protected static string $view;

    /**
     * @var array $data
     */
    protected static array $data = [
        'title' => 'Tomtroc'
    ];

    /**
     * Return static layout.
     *
     * @param $layout
     *
     * @return self
     */
    public static function layout($layout = null): self
    {
        self::$layout = $layout;
        return new self();
    }

    /**
     * Return view instance by static var.
     *
     * @param $view
     *
     * @return self
     */
    public function view($view): self
    {
        self::$view = $view;
        return $this;
    }

    /**
     * Attach data to view
     *
     * @param array $data
     *
     * @return $this
     */
    public function withData(array $data = []): self
    {
        self::$data = [
            ...self::$data,
            ...$data
        ];
        return $this;
    }

    /**
     * Convert templates keys to filepath.
     *
     * @param string $templateKey
     *
     * @return string
     */
    private function parseTemplate(string $templateKey): string
    {
        return __DIR__ . '/../Views/' . str_replace('.', '/', $templateKey) . '.php';
    }

    /**
     * Main render.
     *
     * @throws Exception
     *
     * @return void
     */
    public function render(): void
    {
        $layoutFile = $this->parseTemplate(self::$layout) ?? '';
        $viewFile = $this->parseTemplate(self::$view);

        if (file_exists($viewFile))
        {
            ob_start();
            extract(self::$data);
            require $viewFile;
            $content = ob_get_clean();

            if (file_exists($layoutFile))
            {
                require $layoutFile;
            }
            else
            {
                echo $content;
            }
        }
        else
        {
            throw new Exception("View file not found.");
        }
    }
}
