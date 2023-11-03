<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Input\Http;

use app\Translation\Application\Admin\Language\Create\Command;
use app\Translation\Application\Admin\Language\Create\Port;
use app\Translation\Infrastructure\Input\Http\Model\ErrorResponse;
use app\Translation\Infrastructure\Input\Http\Model\SuccessResponse;
use app\Translation\Infrastructure\Input\Validator\CreateLanguageInputValidator;
use Illuminate\Routing\Controller;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class CreateLanguageController extends Controller
{
    public function __construct(
        private readonly CreateLanguageInputValidator $validator,
        private readonly Port $port,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();
        if (!$this->validator->validate($data)) {
            return (new ErrorResponse('Invalid request data', ResponseAlias::HTTP_BAD_REQUEST))->toJsonResponse();
        }

        $command = new Command($data['language'], $data['rules']);

        try {
            $this->port->handle($command);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                'language' => $command->name,
                'rules' => $command->grammarRules,
            ]);

            return (new ErrorResponse($exception->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR))
                ->toJsonResponse();
        }

        return (new SuccessResponse([]))->toJsonResponse();
    }
}
