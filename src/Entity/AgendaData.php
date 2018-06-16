<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgendaDataRepository")
 */
class AgendaData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Agenda", inversedBy="agendaData")
     * @ORM\JoinColumn(name="agenda_id", referencedColumnName="id")
     */
    private $agenda;

    /**
     * @ORM\ManyToOne(targetEntity="Paciente", inversedBy="agendaData")
     * @ORM\JoinColumn(name="paciente_id", referencedColumnName="id")
     */
    private $paciente;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dataConsulta;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dataConfirmacao;

    /**
     * @ORM\Column(type="boolean")
     */
    private $confirmacaoPeloPaciente;

    /**
     * @ORM\Column(type="boolean")
     */
    private $confirmacao;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pagamentoEfetuado;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dataAtualizacao;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $usuarioAtualizacaoId;

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

    public function getPacienteId(): ?int
    {
        return $this->pacienteId;
    }

    public function setPacienteId(int $pacienteId): self
    {
        $this->pacienteId = $pacienteId;

        return $this;
    }

    public function getDataConsulta(): ?\DateTimeInterface
    {
        return $this->dataConsulta;
    }

    public function setDataConsulta(\DateTimeInterface $dataConsulta): self
    {
        $this->dataConsulta = $dataConsulta;

        return $this;
    }

    public function getDataConfirmacao(): ?\DateTimeInterface
    {
        return $this->dataConfirmacao;
    }

    public function setDataConfirmacao(\DateTimeInterface $dataConfirmacao): self
    {
        $this->dataConfirmacao = $dataConfirmacao;

        return $this;
    }

    public function getConfirmacaoPeloPaciente(): ?bool
    {
        return $this->confirmacaoPeloPaciente;
    }

    public function setConfirmacaoPeloPaciente(bool $confirmacaoPeloPaciente): self
    {
        $this->confirmacaoPeloPaciente = $confirmacaoPeloPaciente;

        return $this;
    }

    public function getConfirmacao(): ?bool
    {
        return $this->confirmacao;
    }

    public function setConfirmacao(bool $confirmacao): self
    {
        $this->confirmacao = $confirmacao;

        return $this;
    }

    public function getPagamentoEfetuado(): ?bool
    {
        return $this->pagamentoEfetuado;
    }

    public function setPagamentoEfetuado(bool $pagamentoEfetuado): self
    {
        $this->pagamentoEfetuado = $pagamentoEfetuado;

        return $this;
    }

    public function getDataAtualizacao(): ?\DateTimeInterface
    {
        return $this->dataAtualizacao;
    }

    public function setDataAtualizacao(\DateTimeInterface $dataAtualizacao): self
    {
        $this->dataAtualizacao = $dataAtualizacao;

        return $this;
    }

    public function getUsuarioAtualizacaoId(): ?int
    {
        return $this->usuarioAtualizacaoId;
    }

    public function setUsuarioAtualizacaoId(int $usuarioAtualizacaoId): self
    {
        $this->usuarioAtualizacaoId = $usuarioAtualizacaoId;

        return $this;
    }
}
