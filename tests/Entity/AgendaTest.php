<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Agenda;
use App\Entity\AgendaConfig;
use App\Entity\AgendaData;
use App\Entity\Medico;

class AgendaTest extends TestCase
{
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

    /**
     * @depends testadicionaAgendaData
     */
    public function testadicionaMedico($agenda) :Agenda
    {
        $medico = new Medico();
        $agenda->setMedico($medico);

        $this->assertInstanceOf("App\Entity\Medico",$agenda->getMedico());        
        return $agenda;
    }
}