<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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

    public function getId()
    {
        return $this->id;
    }

    public function getMedicoId(): ?int
    {
        return $this->medicoId;
    }

    public function setMedicoId(int $medicoId): self
    {
        $this->medicoId = $medicoId;

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
}
