<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Agenda;
use App\Entity\AgendaConfig;
use App\Entity\AgendaData;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class AgendaTest extends TestCase
{

    /**
    * {@inheritDoc}
    */
   protected function setUp()
   {
   }

    public function testNovaAgenda(): Agenda
    {
        $agenda = new Agenda();
        $this->assertInstanceOf("App\Entity\Agenda",$agenda);
        return $agenda;
    }

    /**
     * @depends testNovaAgenda
     */
    public function testadicionaAgendaConfig($agenda) :Agenda
    {
        $agendaConfig = new AgendaConfig();
        $agenda->setAgendaConfig($agendaConfig);
        $this->assertInstanceOf("App\Entity\AgendaConfig",$agenda->getAgendaConfig());
        return $agenda;
    }

    /**
     * @depends testNovaAgenda
     */
    public function testadicionaAgendaData($agenda) :Agenda
    {
        $this->assertCount(0,$agenda->getAgendaData());
        $agendaData = new AgendaData();
        $agenda->addAgendaData($agendaData);

        $this->assertInstanceOf("Doctrine\Common\Collections\Collection",$agenda->getAgendaData());
        $this->assertCount(1,$agenda->getAgendaData());        
        return $agenda;
    }
}