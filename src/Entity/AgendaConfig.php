<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgendaConfigRepository")
 */
class AgendaConfig
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Agenda", mappedBy="agendaConfig")
     */
    private $agenda;

    /**
     * @ORM\Column(type="integer")
     */
    private $valorConsulta;

    public function getId()
    {
        return $this->id;
    }

    public function getAgendaId(): ?int
    {
        return $this->agendaId;
    }

    public function setAgendaId(int $agendaId): self
    {
        $this->agendaId = $agendaId;

        return $this;
    }

    public function getValorConsulta(): ?int
    {
        return $this->valorConsulta;
    }

    public function setValorConsulta(int $valorConsulta): self
    {
        $this->valorConsulta = $valorConsulta;

        return $this;
    }
}
