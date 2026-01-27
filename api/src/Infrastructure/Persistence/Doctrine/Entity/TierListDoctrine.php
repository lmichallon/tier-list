<?php


namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Domain\User\Entity\User;


#[ORM\Entity]
#[ORM\Table(name: 'tier_lists')]
class TierListDoctrine
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\OneToMany(
        mappedBy: 'tierList',
        targetEntity: TierListItemDoctrine::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $items;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->items = new ArrayCollection();
    }

    public function user(): User
    {
        return $this->user;
    }

    public function items(): iterable
    {
        return $this->items;
    }

    public function addItem(TierListItemDoctrine $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }
    }

    public function removeItem(TierListItemDoctrine $item): void
    {
        if ($this->items->removeElement($item)) {
            // orphanRemoval s’en charge
        }
    }
}
