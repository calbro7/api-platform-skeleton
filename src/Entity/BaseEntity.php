<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Loggable;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use Gedmo\SoftDeleteable\SoftDeleteable as SoftDeleteableInterface;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Timestampable as TimestampableInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\MappedSuperclass]
#[Loggable]
#[SoftDeleteable]
abstract class BaseEntity implements SoftDeleteableInterface, TimestampableInterface
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    protected Uuid $id;

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
