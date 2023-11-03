<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Input\Http\Model;

use Illuminate\Http\JsonResponse;

readonly class SuccessResponse
{
    public function __construct(private array $data)
    {
    }

    public function toJsonResponse(): JsonResponse
    {
        return new JsonResponse(['success' => true, 'data' => $this->data, 'error' => null]);
    }
}
