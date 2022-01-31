<?php

namespace App\Entity;

use App\Repository\ConnectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConnectionRepository::class)]
class Connection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $action;

    #[ORM\ManyToOne(targetEntity: Sensor::class, inversedBy: 'connections')]
    #[ORM\JoinColumn(nullable: false)]
    private $sensorA;

    #[ORM\ManyToOne(targetEntity: Sensor::class, inversedBy: 'connections')]
    private $sensorB;

    // used only for easyadmin
    public ?int $sensorAId;
    public ?int $sensorBId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSensorA(): ?Sensor
    {
        return $this->sensorA;
    }

    public function getSensorAId(): ?int
    {
        return $this->sensorBId ?? ($this->getSensorA() ? $this->getSensorA()->getId() : null);
    }

    public function getSensorBId(): ?int
    {
        return $this->sensorBId ?? ($this->getSensorB() ? $this->getSensorB()->getId() : null);
    }

    public function setSensorA($sensorA): self
    {
        $this->sensorA = $sensorA;

        return $this;
    }

    public function getSensorB(): ?Sensor
    {
        return $this->sensorB;
    }

    public function setSensorB($sensorB): self
    {
        $this->sensorB = $sensorB;

        return $this;
    }
}
