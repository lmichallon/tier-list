<?php

namespace App\Interface\Api\Resource\Auth;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Dto\TokenResponse;
use App\Interface\Api\Processor\Auth\LoginProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/auth/login',
            processor: LoginProcessor::class,
            output: TokenResponse::class
        )
    ]
)]
final class LoginResource
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 255)]
    public string $password;
}
