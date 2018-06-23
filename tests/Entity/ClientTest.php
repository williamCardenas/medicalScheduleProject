<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Client;
use App\Entity\Clinica;
use App\Entity\User;

class ClientTest extends TestCase
{

    /**
    * {@inheritDoc}
    */
   protected function setUp()
   {
   }

   public function testNovoCliente() :Client
   {
       $cliente = new Client();
       $this->assertInstanceOf("App\Entity\Client",$cliente);
       return $cliente;
   }

   /**
    * @depends testNovoCliente
    */
   public function testAddClinica($cliente) :Client 
   {
       $this->assertInstanceOf("Doctrine\Common\Collections\Collection",$cliente->getClinic());
       $this->assertCount(0,$cliente->getClinic());

        $clinica = new Clinica();
        $client->addClinic($clinica);
        $this->assertcount(1,$cliente->getClinic());
        return $cliente;
   }
/*
    public function testUnicName(): void
    {
        $pearson = \Faker\Factory::create();

        $client = new Client();
        $client->setName($pearson->name);

        $employeeRepository = $this->createMock(ObjectRepository::class);

        $employeeRepository->expects($this->any())
            ->method('find')
            ->willReturn($client);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($employeeRepository);

        $objectManager->persist($client);
        $objectManager->flush();

        $objectManager->persist($client);
        $objectManager->flush();

    }
*/
}
