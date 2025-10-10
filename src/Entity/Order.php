<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $status = 'Pending';

    #[ORM\Column(length: 20)]
    private ?string $paymentMethod = 'COD'; // Strict COD policy

    #[ORM\OneToMany(mappedBy: 'orderRef', targetEntity: OrderProduct::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): self { $this->status = $status; return $this; }

    public function getPaymentMethod(): ?string { return $this->paymentMethod; }
    public function setPaymentMethod(string $paymentMethod): self { $this->paymentMethod = $paymentMethod; return $this; }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection { return $this->orderProducts; }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setOrderRef($this);
        }
        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            if ($orderProduct->getOrderRef() === $this) {
                $orderProduct->setOrderRef(null);
            }
        }
        return $this;
    }

    public function cancel(): void
    {
        if ($this->status !== 'Delivered') {
            $this->status = 'Cancelled';
        }
    }
}
