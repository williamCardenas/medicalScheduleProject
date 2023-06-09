<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\MedicoRepository;

#[ORM\Entity(repositoryClass:MedicoRepository::class)]
class Medico
{
    const CLASS_NAME = 'Medico';
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    private $nome;

    #[ORM\Column(type:"string", length:255)]
    private $numeroDocumento;

    #[ORM\OneToMany(targetEntity:"Agenda", mappedBy:"medico")]
    private $agenda;

    #[ORM\ManyToOne(targetEntity:"Cliente", inversedBy:"medico")]
    #[ORM\JoinColumn(name:"cliente_id", referencedColumnName:"id")]
    private $cliente;

    #[ORM\Column(type:"string", length:10)]
    private $corAgenda;

    public function __construct(){
        $this->agenda = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getNumeroDocumento(): ?string
    {
        return $this->numeroDocumento;
    }

    public function setNumeroDocumento(string $numeroDocumento): self
    {
        $this->numeroDocumento = $numeroDocumento;

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getAgenda(): ?Collection
    {
        return $this->agenda;
    }

    public function addAgenda(Agenda $agenda) :self
    {
        if(!$this->agenda->contains($agenda)){
            $this->agenda[] = $agenda;
            $agenda->setAgenda($this);
        }
        return $this;
    }

    public function getCorAgenda(): ?String
    {
        return $this->corAgenda;
    }

    public function setCorAgenda(String $corAgenda): self
    {
        $this->corAgenda = $corAgenda;

        return $this;
    }
}
