<?php

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Logo\Entity\Logo;

#[ORM\Entity]
#[ORM\Table(name: 'tier_list_items')]
class TierListItemDoctrine
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: TierListDoctrine::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private TierListDoctrine $tierList;

    #[ORM\ManyToOne(targetEntity: Logo::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Logo $logo;

    #[ORM\Column(type: 'string', length: 10)]
    private string $tier;

    public function __construct(
        TierListDoctrine $tierList,
        Logo $logo,
        string $tier
    ) {
        $this->tierList = $tierList;
        $this->logo = $logo;
        $this->tier = $tier;
    }

    public function logo(): Logo
    {
        return $this->logo;
    }

    public function tier(): string
    {
        return $this->tier;
    }

    public function setTier(string $tier): void
    {
        $this->tier = $tier;
    }

    public function tierList(): TierListDoctrine
    {
        return $this->tierList;
    }
}
