<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgendaDataStatusRepository")
 */

class AgendaDataStatus
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
    private $nome;

    /*
    static agendado     = "agendado";
    static reagendado = "reagendado"; 
    static cancelado = "cancelado";
    static atendido = "atendido";
    */
}
