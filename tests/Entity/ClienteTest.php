<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Cliente;
use App\Entity\Clinica;
use App\Entity\User;

class ClienteTest extends TestCase
{

    /**
    * {@inheritDoc}
    */
   protected function setUp()
   {
   }

   public function testNovoClientee() :Cliente
   {
       $clientee = new Cliente();
       $this->assertInstanceOf("App\Entity\Cliente",$clientee);
       return $clientee;
   }

   /**
    * @depends testNovoClientee
    */
   public function testAddClinica($clientee) :Cliente 
   {
       $this->assertInstanceOf("Doctrine\Common\Collections\Collection",$clientee->getClinic());
       $this->assertCount(0,$clientee->getClinic());

        $clinica = new Clinica();
        $cliente->addClinic($clinica);
        $this->assertcount(1,$clientee->getClinic());
        return $clientee;
   }
/*
    public function testUnicName(): void
    {
        $pearson = \Faker\Factory::create();

        $cliente = new Cliente();
        $cliente->setName($pearson->name);

        $employeeRepository = $this->createMock(ObjectRepository::class);

        $employeeRepository->expects($this->any())
            ->method('find')
            ->willReturn($cliente);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($employeeRepository);

        $objectManager->persist($cliente);
        $objectManager->flush();

        $objectManager->persist($cliente);
        $objectManager->flush();

    }
*/
}
