<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PacienteRepository;

#[ORM\Entity(repositoryClass:PacienteRepository::class)]
class Paciente
{
    const CLASS_NAME = 'Paciente';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    private $nome;

    #[ORM\Column(type:"string", length:255)]
    private $apelido;

    #[ORM\Column(type:"integer")]
    private $idade;

    #[ORM\OneToMany(targetEntity:"AgendaData", mappedBy:"paciente")]
    private $agendaData;

    #[ORM\ManyToOne(targetEntity:"Cliente", inversedBy:"paciente")]
    #[ORM\JoinColumn(name:"cliente_id", referencedColumnName:"id")]
    private $cliente;


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

    public function getApelido(): ?string
    {
        return $this->apelido;
    }

    public function setApelido(string $apelido): self
    {
        $this->apelido = $apelido;

        return $this;
    }

    public function getIdade(): ?int
    {
        return $this->idade;
    }

    public function setIdade(int $idade): self
    {
        $this->idade = $idade;

        return $this;
    }

    public function getCliente(): Cliente
    {
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }
}
