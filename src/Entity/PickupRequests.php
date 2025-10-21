<?php

namespace App\Entity;

use App\Repository\PickupRequestsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PickupRequestsRepository::class)]
class PickupRequests
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $container_id = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $requested_at = null;

    #[ORM\Column]
    private ?int $assigned_driver_id = null;

    #[ORM\Column]
    private ?\DateTime $pickup_date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getContainerId(): ?int
    {
        return $this->container_id;
    }

    public function setContainerId(int $container_id): static
    {
        $this->container_id = $container_id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRequestedAt(): ?\DateTimeImmutable
    {
        return $this->requested_at;
    }

    public function setRequestedAt(\DateTimeImmutable $requested_at): static
    {
        $this->requested_at = $requested_at;

        return $this;
    }

    public function getAssignedDriverId(): ?int
    {
        return $this->assigned_driver_id;
    }

    public function setAssignedDriverId(int $assigned_driver_id): static
    {
        $this->assigned_driver_id = $assigned_driver_id;

        return $this;
    }

    public function getPickupDate(): ?\DateTime
    {
        return $this->pickup_date;
    }

    public function setPickupDate(\DateTime $pickup_date): static
    {
        $this->pickup_date = $pickup_date;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
