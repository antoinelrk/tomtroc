<?php

namespace App\Helpers;

use App\Core\Facades\View;

class Errors
{
    /**
     * Return a specific error response.
     *
     * @param string $message
     * @param int $code
     *
     * @return View
     */
    public static function set(string $message, int $code = 400): View
    {
        return self::render(
            $message,
            $code,
        );
    }

    /**
     * Return 404 default page.
     *
     * @return View
     */
    public static function notFound(): View
    {
        return self::render(
            'Not found',
            404
        );
    }

    /**
     * Return formatted error page
     *
     * @param string $message
     * @param int $code
     *
     * @return View
     */
    protected static function render(string $message, int $code): View {
        return View::layout('layouts.empty')
            ->withData([
                'error' => [
                    'code' => $code,
                    'message' => $message
                ],
            ])
            ->view('errors.http-errors')
            ->render();
    }
}
