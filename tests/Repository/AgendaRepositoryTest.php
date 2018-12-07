<?php

namespace App\Tests\Repository;

use App\Entity\Agenda;
use App\Entity\User;
use App\Entity\Cliente;
use App\Entity\Clinica;
use App\Entity\Medico;
use App\Repository\AgendaRepository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
/*
class AgendaRepositoryTest extends KernelTestCase
{
    private $AgendaRepository;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->AgendaRepository = $this->entityManager
        ->getRepository(Agenda::class);

    }

    public function testRepositoryInstance()
    {
        $this->assertInstanceOf('App\Repository\AgendaRepository',$this->AgendaRepository);
    }
    
}
*/