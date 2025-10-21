<?php

namespace App\Entity;

use App\Repository\ContainerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContainerRepository::class)]
class Container
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // ✅ Relation to Order (each container belongs to one order)
    #[ORM\ManyToOne(inversedBy: 'containers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    // ✅ Relation to Rider (each container is assigned to one rider)
    #[ORM\ManyToOne(inversedBy: 'containers')]
    private ?Rider $rider = null;

    // ✅ The user who made the order (if needed)
    #[ORM\Column(nullable: true)]
    private ?int $user_id = null;

    // ✅ Container status (e.g. pending, picked-up, delivered)
    #[ORM\Column(length: 50)]
    private ?string $status = null;

    // ✅ Optional: Timestamps for tracking
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $pickup_time = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $delivered_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;
        return $this;
    }

    public function getRider(): ?Rider
    {
        return $this->rider;
    }

    public function setRider(?Rider $rider): static
    {
        $this->rider = $rider;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): static
    {
        $this->user_id = $user_id;
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

    public function getPickupTime(): ?\DateTimeImmutable
    {
        return $this->pickup_time;
    }

    public function setPickupTime(?\DateTimeImmutable $pickup_time): static
    {
        $this->pickup_time = $pickup_time;
        return $this;
    }

    public function getDeliveredAt(): ?\DateTimeImmutable
    {
        return $this->delivered_at;
    }

    public function setDeliveredAt(?\DateTimeImmutable $delivered_at): static
    {
        $this->delivered_at = $delivered_at;
        return $this;
    }
}
