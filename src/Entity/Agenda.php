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
     * @ORM\ManyToOne(targetEntity="Clinica", inversedBy="agenda")
     * @ORM\JoinColumn(name="clinica_id", referencedColumnName="id")
     */
    private $clinica;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $horarioInicioAtendimento;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $horarioFimAtendimento;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dataInicioAtendimento;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dataFimAtendimento;

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
        return $this->horarioInicioAtendimento;
    }

    public function setHorarioInicioAtendimento(?\DateTimeInterface $horarioInicioAtendimento): self
    {
        $this->horarioInicioAtendimento = $horarioInicioAtendimento;

        return $this;
    }

    public function getHorarioFimAtendimento(): ?\DateTimeInterface
    {
        return $this->horarioFimAtendimento;
    }

    public function setHorarioFimAtendimento(?\DateTimeInterface $horarioFimAtendimento): self
    {
        $this->horarioFimAtendimento = $horarioFimAtendimento;

        return $this;
    }

    public function getdataInicioAtendimento(): ?\DateTimeInterface
    {
        return $this->dataInicioAtendimento;
    }

    public function setdataInicioAtendimento(?\DateTimeInterface $dataInicioAtendimento): self
    {
        $this->dataInicioAtendimento = $dataInicioAtendimento;

        return $this;
    }

    public function getdataFimAtendimento(): ?\DateTimeInterface
    {
        return $this->dataFimAtendimento;
    }

    public function setdataFimAtendimento(?\DateTimeInterface $dataFimAtendimento): self
    {
        $this->dataFimAtendimento = $dataFimAtendimento;

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


    public function getClinica(): ?Clinica
    {
        return $this->clinica;
    }

    public function setClinica(Clinica $clinica): self
    {
        $this->clinica = $clinica;

        return $this;
    }
}
