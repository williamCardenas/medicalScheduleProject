<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteRepository")
 */
class Cliente
{
    const CLASS_NAME = 'cliente';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nome;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="clienteId", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Clinica", mappedBy="cliente", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $clinica;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->clinica = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUser()
    {
        return $this->user;
    }


    public function addUser(User $user)
    {
        if ($this->user->contains($user)) {
           return;
        }

        $this->user[] = $user;
        $user->setCliente($this);
    }

    public function removeUser(User $user)
    {
        $this->user->removeElement($user);
        $user->setCategory(null);
    }

    public function getClinica() :Collection
    {
        return $this->getClinica;
    }

    public function addClinica(Clinica $clinica) :Cliente
    {
        if (!$this->clinica->contains($clinica)) {
           return $this;
        }

        $this->user[] = $user;
        $user->setCliente($this);
        return $this;
    }

}
