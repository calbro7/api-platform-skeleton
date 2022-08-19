<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Loggable;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use Gedmo\SoftDeleteable\SoftDeleteable as SoftDeleteableInterface;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Timestampable as TimestampableInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Ulid;

#[ORM\MappedSuperclass]
#[Loggable]
#[SoftDeleteable]
abstract class BaseEntity implements SoftDeleteableInterface, TimestampableInterface
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    protected Ulid $id;

    public function getId(): ?Ulid
    {
        return $this->id;
    }
}
