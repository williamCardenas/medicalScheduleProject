<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Paciente;
use App\Entity\Cliente;

class PacienteTest extends TestCase
{

    /**
    * {@inheritDoc}
    */
   protected function setUp()
   {
   }

   public function testNovoPaciente() :Paciente
   {
       $paciente = new Paciente();
       $this->assertInstanceOf("App\Entity\Paciente",$paciente);
       return $paciente;
   }

   /**
    * @depends testNovoPaciente
    */
   public function testAddClinica($paciente) :Paciente 
   {
        $cliente = new Cliente();
        $paciente->setCliente($cliente);
        $this->assertInstanceOf("App\Entity\Cliente",$paciente->getCliente());
      
        return $paciente;
   }

}
