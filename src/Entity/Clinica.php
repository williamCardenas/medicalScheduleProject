<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClinicRepository")
 */
class Clinica
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="clinica")
     * @ORM\JoinColumn(name="clinica_id", referencedColumnName="id")
     */
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

    public function getClienteId(): ?int
    {
        return $this->clienteId;
    }

    public function setClienteId(int $clienteId): self
    {
        $this->clienteId = $clienteId;

        return $this;
    }
}
