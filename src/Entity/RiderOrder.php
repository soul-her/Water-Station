<?php

namespace App\Entity;

use App\Repository\RiderOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RiderOrderRepository::class)]
class RiderOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTime $pickupTime = null;

    #[ORM\Column]
    private ?\DateTime $deliveredAt = null;

    #[ORM\Column]
    private ?int $container_id = null;

    #[ORM\Column(length: 50)]
    private ?string $containerStatus = null;

    #[ORM\ManyToOne(inversedBy: 'riderOrders')]
    private ?Rider $rider = null;

    /**
     * @var Collection<int, Container>
     */
    #[ORM\OneToMany(targetEntity: Container::class, mappedBy: 'riderOrder')]
    private Collection $containers;

    public function __construct()
    {
        $this->containers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPickupTime(): ?\DateTime
    {
        return $this->pickupTime;
    }

    public function setPickupTime(\DateTime $pickupTime): static
    {
        $this->pickupTime = $pickupTime;

        return $this;
    }

    public function getDeliveredAt(): ?\DateTime
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(\DateTime $deliveredAt): static
    {
        $this->deliveredAt = $deliveredAt;

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

    public function getContainerStatus(): ?string
    {
        return $this->containerStatus;
    }

    public function setContainerStatus(string $containerStatus): static
    {
        $this->containerStatus = $containerStatus;

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

    /**
     * @return Collection<int, Container>
     */
    public function getContainers(): Collection
    {
        return $this->containers;
    }

    public function addContainer(Container $container): static
    {
        if (!$this->containers->contains($container)) {
            $this->containers->add($container);
            $container->setRiderOrder($this);
        }

        return $this;
    }

    public function removeContainer(Container $container): static
    {
        if ($this->containers->removeElement($container)) {
            // set the owning side to null (unless already changed)
            if ($container->getRiderOrder() === $this) {
                $container->setRiderOrder(null);
            }
        }

        return $this;
    }
}
