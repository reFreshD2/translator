<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Input\Http;

use app\Translation\Application\LexicalAnalyze\Make\Command;
use app\Translation\Application\LexicalAnalyze\Make\Handler;
use app\Translation\Application\LexicalAnalyze\Make\Port;
use app\Translation\Domain\Exception\UnsupportedTokenException;
use app\Translation\Domain\Repository\Exception\NotFoundException;
use app\Translation\Infrastructure\Input\Http\Model\ErrorResponse;
use app\Translation\Infrastructure\Input\Http\Model\SuccessResponse;
use app\Translation\Infrastructure\Input\Validator\MakeLexicalAnalyzeInputValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class MakeLexicalAnalyzeController extends Controller
{
    public function __construct(
        private readonly MakeLexicalAnalyzeInputValidator $validator,
        private readonly Port $port,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function make(Request $request): JsonResponse
    {
        if (!$this->validator->validate($request->toArray())) {
            return (new ErrorResponse('Invalid request data', ResponseAlias::HTTP_BAD_REQUEST))->toJsonResponse();
        }

        $command = new Command($request->get('language'), $request->get('input'));

        try {
            $result = $this->port->handle($command);
        } catch (NotFoundException|UnsupportedTokenException $exception) {
            $this->logger->warning($exception->getMessage(), array_merge([
                'language' => $command->languageName,
                'code' => $command->code,
            ], $exception->getContext()));

            return (new ErrorResponse($exception->getMessage(), ResponseAlias::HTTP_BAD_REQUEST))->toJsonResponse();
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                'language' => $command->languageName,
                'code' => $command->code,
            ]);

            return (new ErrorResponse($exception->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR))
                ->toJsonResponse();
        }

        return (new SuccessResponse($result))->toJsonResponse();
    }
}
