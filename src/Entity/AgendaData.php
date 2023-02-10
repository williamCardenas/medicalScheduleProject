<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgendaDataRepository;

#[ORM\Entity(repositoryClass: AgendaDataRepository::class)]
class AgendaData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    
    #[ORM\ManyToOne(targetEntity:Agenda::class, inversedBy:"agendaData")]
    #[ORM\JoinColumn(name:"agenda_id", referencedColumnName:"id")]
    private $agenda;

    
    #[ORM\ManyToOne(targetEntity:Paciente::class, inversedBy:"agendaData")]
    #[ORM\JoinColumn(name:"paciente_id", referencedColumnName:"id")]
    private $paciente;

    
    #[ORM\Column(type:"datetime")]
    private $dataConsulta;

    #[ORM\Column(type:"datetime", nullable:true)]
    private $dataConfirmacao;

    #[ORM\Column(type:"boolean", nullable:true)]
    private $confirmacaoPeloPaciente;

    #[ORM\Column(type:"boolean", nullable:true ,options:["usigned"=>true, "default"=>false])]
    private $confirmacao;

    #[ORM\Column(type:"boolean", nullable:true ,options:["usigned"=>true, "default"=>false])]
    private $pagamentoEfetuado;

    #[ORM\Column(type:"datetime")]
    private $dataAtualizacao;

    #[ORM\ManyToOne(targetEntity:AgendaDataStatus::class)]
    #[ORM\JoinColumn(name:"status", referencedColumnName:"id")]
    private $status;

    #[ORM\ManyToOne(targetEntity:"User")]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    private $usuarioAtualizacaoId;

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

    public function getPaciente(): ?Paciente
    {
        return $this->paciente;
    }

    public function setPaciente(Paciente $paciente): self
    {
        $this->paciente = $paciente;

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

    public function getUsuarioAtualizacaoId(): ?User
    {
        return $this->usuarioAtualizacaoId;
    }

    public function setUsuarioAtualizacaoId(User $usuarioAtualizacaoId): self
    {
        $this->usuarioAtualizacaoId = $usuarioAtualizacaoId;

        return $this;
    }

    public function getStatus(): ? User
    {
        return $this->status;
    }

    public function setStatus(AgendaDataStatus $status): self
    {
        $this->status = $status;

        return $this;
    }
}
