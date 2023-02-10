<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Agenda;
use App\Entity\AgendaConfig;

class AgendaConfigTest extends TestCase
{
    public function testNovaAgendaConfig(): AgendaConfig
    {
        $agendaConfig = new AgendaConfig();
        $this->assertInstanceOf("App\Entity\AgendaConfig",$agendaConfig);
        return $agendaConfig;   
    }

    /**
     * @depends testNovaAgendaConfig
     */
    public function testSetAgenda($agendaConfig): AgendaConfig
    {
        $agenda = new Agenda();
        $agendaConfig->setAgenda($agenda);
        $this->assertInstanceOf("App\Entity\Agenda",$agendaConfig->getAgenda());
        return $agendaConfig;
    }
}