<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgendaConfigRepository;

#[ORM\Entity(repositoryClass:AgendaConfigRepository::class)]
class AgendaConfig
{
    const CLASS_NAME = "Configuração";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\OneToOne(targetEntity:Agenda::class, mappedBy:"agendaConfig", cascade:["persist","remove"])]
    #[ORM\JoinColumn(name:"agenda_id", referencedColumnName:"id")]
    private $agenda;

    #[ORM\Column(type:"float")]
    private $valorConsulta = 10000;

    #[ORM\Column(type:"integer")]
    private $duracaoConsulta = 30;

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
