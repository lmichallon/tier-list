<?php

namespace App\Domain\Logo\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'logos')]
class Logo
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $company;

    #[ORM\Column(length: 512)]
    private string $imageURL;

    public function __construct(string $company, string $imageURL)
    {
        $this->company = $company;
        $this->imageURL = $imageURL;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getImageURL(): string
    {
        return $this->imageURL;
    }
}
