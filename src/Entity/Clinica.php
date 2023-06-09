<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use app\Entity\Cliente;
use App\Repository\ClinicaRepository;

#[ORM\Entity(repositoryClass:ClinicaRepository::class)]
class Clinica
{
    const CLASS_NAME = 'Clinica';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    private $nome;

    #[ORM\ManyToOne(targetEntity:"Cliente", inversedBy:"clinica")]
    #[ORM\JoinColumn(name:"cliente_id", referencedColumnName:"id",nullable:true)]
    private $cliente;

    #[ORM\OneToMany(targetEntity:"Agenda", mappedBy:"clinica")]
    private $agenda;

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

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }
}
