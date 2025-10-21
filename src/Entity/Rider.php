<?php

namespace App\Entity;

use App\Repository\RiderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RiderRepository::class)]
class Rider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    /**
     * @var Collection<int, RiderOrder>
     */
    #[ORM\OneToMany(targetEntity: RiderOrder::class, mappedBy: 'rider')]
    private Collection $riderOrders;

    /**
     * @var Collection<int, Container>
     */
    #[ORM\OneToMany(targetEntity: Container::class, mappedBy: 'rider')]
    private Collection $containers;

    public function __construct()
    {
        $this->riderOrders = new ArrayCollection();
        $this->containers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
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

    /**
     * @return Collection<int, RiderOrder>
     */
    public function getRiderOrders(): Collection
    {
        return $this->riderOrders;
    }

    public function addRiderOrder(RiderOrder $riderOrder): static
    {
        if (!$this->riderOrders->contains($riderOrder)) {
            $this->riderOrders->add($riderOrder);
            $riderOrder->setRider($this);
        }

        return $this;
    }

    public function removeRiderOrder(RiderOrder $riderOrder): static
    {
        if ($this->riderOrders->removeElement($riderOrder)) {
            if ($riderOrder->getRider() === $this) {
                $riderOrder->setRider(null);
            }
        }

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
            $container->setRider($this);
        }

        return $this;
    }

    public function removeContainer(Container $container): static
    {
        if ($this->containers->removeElement($container)) {
            if ($container->getRider() === $this) {
                $container->setRider(null);
            }
        }

        return $this;
    }
}
