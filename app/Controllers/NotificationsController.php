<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Notification;
use App\Core\Response;

class NotificationsController extends Controller
{
    /**
     * Drop the notification
     *
     * @param $id
     * @return void
     */
    public function drop($id): void
    {
        if (isset($id)) {
            Notification::drop($id);
            Response::json(null, Response::HTTP_NO_CONTENT);
        } else {
            Response::json(null, Response::HTTP_BAD_REQUEST);
        }
    }
}
