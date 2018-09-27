<?php

namespace App\Tests\Validation;

use App\Entity\Agenda;
use App\Entity\User;
use App\Entity\Cliente;
use App\Entity\Clinica;
use App\Entity\Medico;
use App\Validator\Constraints\AgendaContainsValidator;

use PHPUnit\Framework\TestCase;
use DateTime;

class AgendaTest extends TestCase
{
    private $agendaValidator;

    protected function setUp()
    {
        $this->agendaValidator = new AgendaContainsValidator();
    }

    public function testPrimeiraAgenda()
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new DateTime('2018-10-01'));
        $agenda->setdataFimAtendimento(new DateTime('2018-10-30'));
        
        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,[]);

        $this->assertTrue($resultado);

        return [$agenda];        
    }

    /**
     * @depends testPrimeiraAgenda
     */
    public function testInserirMesmaAgenda(Array $agendas)
    {
        $resultado = $this->agendaValidator->validaDataDisponivel($agendas[0],$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }
    
     /**
     * @depends testInserirMesmaAgenda
     */
    public function testNovaAgendaComDataInicialMaiorEDataFinalIgual(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new DateTime('2018-10-10'));
        $agenda->setdataFimAtendimento(new DateTime('2018-10-30'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testNovaAgendaComDataInicialMaiorEDataFinalIgual
     */
    public function testNovaAgendaComDataInicialMaiorEDataFinalMaior(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new DateTime('2018-10-10'));
        $agenda->setDataFimAtendimento(new DateTime('2018-11-02'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testNovaAgendaComDataInicialMaiorEDataFinalMaior
     */
    public function testNovaAgendaSemSobreporDatas(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new DateTime('2018-11-01'));
        $agenda->setDataFimAtendimento(new DateTime('2018-11-05'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertTrue($resultado);
        array_push($agendas,$agenda);
        return $agendas;
    }

    /**
     * @depends testNovaAgendaSemSobreporDatas
     */
    public function testAgendaSobrepondoDataFinal(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new DateTime('2018-09-10'));
        $agenda->setdataFimAtendimento(new DateTime('2018-10-15'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        array_push($agendas,$agenda);
        return $agendas;
    }

    /**
     * @depends testAgendaSobrepondoDataFinal
     */
    public function testAgendacomDataInicialmenorEDataFinalMaior(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new DateTime('2018-09-01'));
        $agenda->setdataFimAtendimento(new DateTime('2018-12-15'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        array_push($agendas,$agenda);
        return $agendas;
    }

    /**
     * @depends testAgendaSobrepondoDataFinal
     */
    public function testAgendacomDataInicialmaiorEDataFinalMenor(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new DateTime('2018-10-05'));
        $agenda->setdataFimAtendimento(new DateTime('2018-10-10'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        array_push($agendas,$agenda);
        return $agendas;
    }
}