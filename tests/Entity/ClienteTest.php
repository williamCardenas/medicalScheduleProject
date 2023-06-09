<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Cliente;
use App\Entity\Clinica;
use App\Entity\Paciente;
use App\Entity\User;

class ClienteTest extends TestCase
{
   public function testNovoCliente() :Cliente
   {
       $cliente = new Cliente();
       $this->assertInstanceOf("App\Entity\Cliente",$cliente);
       return $cliente;
   }

   /**
    * @depends testNovoCliente
    */
   public function testAddClinica($cliente) :Cliente 
   {
       $this->assertInstanceOf("Doctrine\Common\Collections\Collection",$cliente->getClinica());
       $this->assertCount(0,$cliente->getClinica());

        $clinica = new Clinica();
        $cliente->addClinica($clinica);
        $this->assertcount(1,$cliente->getClinica());
        return $cliente;
   }

   /**
    * @depends testAddClinica
    */
    public function testAddDuasClinicas($cliente) :Cliente 
    {
        $this->assertInstanceOf("Doctrine\Common\Collections\Collection",$cliente->getClinica());
        $this->assertCount(1,$cliente->getClinica());
 
         $clinica = new Clinica();
         $cliente->addClinica($clinica);
         $this->assertcount(2,$cliente->getClinica());
         return $cliente;
    }

    /**
    * @depends testNovoCliente
    */
    public function testAddPaciente($cliente) :Cliente 
    {
        $this->assertInstanceOf("Doctrine\Common\Collections\Collection",$cliente->getPacientes());
        $this->assertCount(0,$cliente->getPacientes());
 
        $paciente = new Paciente();
        $cliente->addPaciente($paciente);
        $this->assertcount(1,$cliente->getPacientes());
        return $cliente;
    }
}
