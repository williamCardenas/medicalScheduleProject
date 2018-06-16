<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AgendaRepository")
 */
class Agenda
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Medico", inversedBy="agenda")
     * @ORM\JoinColumn(name="medico_id", referencedColumnName="id")
     */
    private $medico;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $horarioInicioAtendimento;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $horarioFimAtendimento;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fimDeSemana;

    /**
     * @ORM\OneToOne(targetEntity="AgendaConfig", inversedBy="agenda")
     * @ORM\JoinColumn(name="agenda_id", referencedColumnName="id")
     */
    private $agendaConfig;

    /**
     * @ORM\OneToMany(targetEntity="AgendaData", mappedBy="agenda")
     */
    private $agendaData;

    public function __construct(){
        $this->agendaData = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMedico(): ?Medico
    {
        return $this->medico;
    }

    public function setMedico(Medico $medico): self
    {
        $this->medico = $medico;

        return $this;
    }

    public function getHorarioInicioAtendimento(): ?\DateTimeInterface
    {
        return $this->HorarioInicioAtendimento;
    }

    public function setHorarioInicioAtendimento(?\DateTimeInterface $HorarioInicioAtendimento): self
    {
        $this->HorarioInicioAtendimento = $HorarioInicioAtendimento;

        return $this;
    }

    public function getHorarioFimAtendimento(): ?\DateTimeInterface
    {
        return $this->HorarioFimAtendimento;
    }

    public function setHorarioFimAtendimento(?\DateTimeInterface $HorarioFimAtendimento): self
    {
        $this->HorarioFimAtendimento = $HorarioFimAtendimento;

        return $this;
    }

    public function getFimDeSemana(): ?bool
    {
        return $this->fimDeSemana;
    }

    public function setFimDeSemana(bool $fimDeSemana): self
    {
        $this->fimDeSemana = $fimDeSemana;

        return $this;
    }

    public function getAgendaConfig(): ?AgendaConfig
    {
        return $this->agendaConfig;
    }

    public function setAgendaConfig(AgendaConfig $agendaConfig): self
    {
        $this->agendaConfig = $agendaConfig;

        return $this;
    }

    public function getAgendaData(): ?Collection
    {
        return $this->agendaData;
    }

    public function addAgendaData(AgendaData $agendaData) :self
    {
        if(!$this->agendaData->contains($agendaData)){
            $this->agendaData[] = $agendaData;
            $agendaData->setAgenda($this);
        }
        return $this;
    }
}
