<?php

namespace App\Helpers;

use App\Core\Facades\View;
use App\Core\Response;

class Errors
{
    /**
     * Return a specific error response.
     *
     * @param string $message
     * @param int $code
     *
     * @return View|null
     */
    public static function set(string $message, int $code = 400): ?View
    {
        return self::render(
            $message,
            $code,
        );
    }

    /**
     * Return 404 default page.
     *
     * @return View|null
     */
    public static function notFound(): ?View
    {
        return self::render(
            'Not found',
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Return xsrf expired error page.
     *
     * @return View|null
     */
    public static function expired(): ?View
    {
        return self::render(
            'Page expired',
            Response::PAGE_EXPIRED
        );
    }

    /**
     * Return formatted error page
     *
     * @param string $message
     * @param int $code
     *
     * @return View|null
     */
    protected static function render(string $message, int $code): ?View
    {
        return View::layout('layouts.app')
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
