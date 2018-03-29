<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Client;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class ClientTest extends TestCase
{

    /**
    * {@inheritDoc}
    */
   protected function setUp()
   {
   }

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
}
