<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="clientId", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Clinica", mappedBy="client", orphanRemoval=true)
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
        $user->setClient($this);
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

    public function addClinica(Clinica $clinica) :Client
    {
        if (!$this->clinica->contains($clinica)) {
           return $this;
        }

        $this->user[] = $user;
        $user->setClient($this);
        return $this;
    }

}
