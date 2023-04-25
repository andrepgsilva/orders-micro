<?php

namespace App\Modules\Cart\Connectors\Adapters\Secondary\Helpers;

abstract class CheckResponseFailed
{
    /**
     * Determine if the response indicates a client error occurred.
     *
     * @return bool
     */
    public function clientError(int $status)
    {
        return $status >= 400 && $status < 500;
    }

    /**
     * Determine if the response indicates a server error occurred.
     *
     * @return bool
     */
    public function serverError(int $status): bool
    {
        return $status >= 500;
    }

    /**
     * Determine if the response indicates a client or server error occurred.
     *
     * @return bool
     */
    public function failed(int $status): bool
    {
        return $this->serverError($status) || $this->clientError($status);
    }
}