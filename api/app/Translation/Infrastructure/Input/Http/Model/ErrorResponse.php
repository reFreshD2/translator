<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Input\Http\Model;

use Illuminate\Http\JsonResponse;

readonly class ErrorResponse
{
    public function __construct(private string $reason, private int $code)
    {
    }

    public function toJsonResponse(): JsonResponse
    {
        return new JsonResponse(
            ['success' => false, 'data' => [], 'error' => $this->reason],
            $this->code,
        );
    }
}
