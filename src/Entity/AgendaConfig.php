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

    /**
     * @ORM\Column(type="integer")
     */
    private $duracaoConsulta;

    public function getId()
    {
        return $this->id;
    }

    public function getAgenda(): ?Agenda
    {
        return $this->agenda;
    }

    public function setAgenda(Agenda $agenda): self
    {
        $this->agenda = $agenda;

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

    public function getDuracaoConsulta(): ?int
    {
        return $this->duracaoConsulta;
    }

    public function setDuracaoConsulta(int $duracaoConsulta): self
    {
        $this->duracaoConsulta = $duracaoConsulta;

        return $this;
    }
}
