<?php

namespace App\Entity;

use App\Repository\SensorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SensorRepository::class)]
class Sensor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $sensorType;

    #[ORM\Column(type: 'string', length: 255)]
    private $externalId;

    #[ORM\ManyToOne(targetEntity: Network::class, inversedBy: 'sensors')]
    private $network;

    #[ORM\OneToMany(mappedBy: 'sensorA', targetEntity: Connection::class)]
    private $connections;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $value;

    public function __construct()
    {
        $this->connections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSensorType(): ?string
    {
        return $this->sensorType;
    }

    public function setSensorType(string $sensorType): self
    {
        $this->sensorType = $sensorType;

        return $this;
    }

    public function getNetwork(): ?Network
    {
        return $this->network;
    }

    public function setNetwork(?Network $network): self
    {
        $this->network = $network;

        return $this;
    }

    /**
     * @return Collection|Connection[]
     */
    public function getConnections(): Collection
    {
        return $this->connections;
    }

    public function addConnection(Connection $connection): self
    {
        if (!$this->connections->contains($connection)) {
            $this->connections[] = $connection;
            $connection->setSensorA($this);
        }

        return $this;
    }

    public function removeConnection(Connection $connection): self
    {
        if ($this->connections->removeElement($connection)) {
            // set the owning side to null (unless already changed)
            if ($connection->getSensorA() === $this) {
                $connection->setSensorA(null);
            }
        }

        return $this;
    }

    public function getExternalId()
    {
        return $this->externalId;
    }

    public function setExternalId($externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function setValueFromObject($object): void
    {
        switch ($this->getSensorType()) {
            case 'Temperature':
                $this->value = $object->temperature;
                break;
            case 'Light':
                $this->value = $object->state;
                break;
        }
    }
}
