<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass:"App\Repository\ClienteRepository")]
class Cliente
{
    const CLASS_NAME = 'Cliente';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type:"string", length:255, unique:true)]
    private $nome;

    #[ORM\OneToMany(targetEntity:User::class, mappedBy:"clienteId", orphanRemoval:true)]
    #[ORM\JoinColumn(nullable:true)]
    private $user;

    #[ORM\OneToMany(targetEntity:Clinica::class, mappedBy:"cliente", orphanRemoval:true)]
    private $clinica;

    #[ORM\OneToMany(targetEntity:Medico::class, mappedBy:"cliente")]
    private $medico;

    #[ORM\OneToMany(targetEntity:Paciente::class, mappedBy:"cliente")]
    private $paciente;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->clinica = new ArrayCollection();
        $this->paciente = new ArrayCollection();
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

    /**
     * @return Collection|Clinica[]
     */
    public function getClinica() :ArrayCollection
    {
        return $this->clinica;
    }

    public function addClinica(Clinica $clinica)
    {
        if ($this->clinica->contains($clinica)) {
           return $this;
        }
        $this->clinica[] = $clinica;
        $clinica->setCliente($this);
    }

    /**
     * @return Collection|Medico[]
     */
    public function getMedico() :ArrayCollection
    {
        return $this->medico;
    }

    /**
     * @param $medico
     * @return Void
     */
    public function addmedico(Medico $medico) :void
    {
        if (!$this->medico->contains($medico)) {
            $this->medico[] = $medico;
            $medico->setCliente($this);
        }

    }

    /**
     * @return Collection|Paciente[]
     */
    public function getPacientes() :ArrayCollection
    {
        return $this->paciente;
    }

    /**
     * @param $paciente
     * @return Void
     */
    public function addPaciente(Paciente $paciente) :void
    {
        if (!$this->paciente->contains($paciente)) {
            $this->paciente[] = $paciente;
            $paciente->setCliente($this);
           
        }
    }


}
