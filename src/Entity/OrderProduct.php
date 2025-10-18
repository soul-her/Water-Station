<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
#[ORM\Table(name: "order_product")]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $product_name = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "orderProducts")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Order $order = null;

    // Getters and Setters
    public function getId(): ?int { return $this->id; }

    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getProductName(): ?string { return $this->product_name; }
    public function setProductName(string $product_name): self { $this->product_name = $product_name; return $this; }

    public function getOrder(): ?Order { return $this->order; }
    public function setOrder(?Order $order): self { $this->order = $order; return $this; }
}
