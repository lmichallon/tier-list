<?php

namespace App\Interface\Api\Resource\Auth;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Interface\Api\Processor\Auth\RegisterProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/auth/register',
            processor: RegisterProcessor::class,
            output: false,
            status: 201
        )
    ]
)]
final class RegisterResource
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 255)]
    public string $password;
}
