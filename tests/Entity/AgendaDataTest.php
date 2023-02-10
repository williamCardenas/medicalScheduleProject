<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Agenda;
use App\Entity\AgendaData;
use App\Entity\Paciente;

class AgendaDataTest extends TestCase
{
    public function testNovaAgendaData(): AgendaData
    {
        $agendaData = new AgendaData();
        $this->assertInstanceOf("App\Entity\AgendaData",$agendaData);
        return $agendaData;   
    }

    /**
     * @depends testNovaAgendaData
     */
    public function testSetAgenda($agendaData): AgendaData
    {
        $agenda = new Agenda();
        $agendaData->setAgenda($agenda);
        $this->assertInstanceOf("App\Entity\Agenda",$agendaData->getAgenda());
        return $agendaData;
    }

    /**
     * @depends testNovaAgendaData
     */
    public function testSetPaciente($agendaData): AgendaData
    {
        $paciente = new Paciente();
        $agendaData->setPaciente($paciente);
        $this->assertInstanceOf("App\Entity\Paciente",$agendaData->getPaciente());
        return $agendaData;
    }
}