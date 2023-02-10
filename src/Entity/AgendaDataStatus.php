<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgendaDataStatusRepository;

#[ORM\Entity(repositoryClass:AgendaDataStatusRepository::class)]
class AgendaDataStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type:"string", length:255, unique:true)]
    private $nome;

    public function getNome():String 
    {
        return $this->nome;
    }
}
