<?php

namespace App\Interface\Api\Processor\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Auth\RegisterUserService;
use App\Interface\Api\Resource\Auth\RegisterResource;
use Symfony\Component\HttpFoundation\Response;

final class RegisterProcessor implements ProcessorInterface
{
    public function __construct(
        private RegisterUserService $registerUserService
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): Response {
        /** @var RegisterResource $data */
        $this->registerUserService->register($data->email, $data->password);

        return new Response(null, Response::HTTP_CREATED);
    }
}
