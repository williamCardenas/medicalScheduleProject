<?php

namespace App\Tests\Validation;

use App\Entity\Agenda;
use App\Entity\User;
use App\Entity\Cliente;
use App\Entity\Clinica;
use App\Entity\Medico;
use App\Validator\Constraints\AgendaConstraints;
use App\Validator\Constraints\AgendaConstraintsValidator;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class AgendaTest extends TestCase
{
    private $agendaValidator;

    protected function setUp()
    {
        $objectManager = $this->createMock(EntityManager::class);
        $this->agendaValidator = new AgendaConstraintsValidator($objectManager);
    }

    public function testPrimeiraAgendaComDatas()
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-30'));
        
        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,[]);

        $this->assertTrue($resultado);

        return [$agenda];        
    }
    
     /**
     * @depends testPrimeiraAgendaComDatas
     */
    public function testNovaAgendaComDataInicialMaiorEDataFinalIgual(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-10'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-30'));

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
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-10'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-11-02'));

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
        $agenda->setDataInicioAtendimento(new \DateTime('2018-11-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-11-05'));
        $agenda->setId(1);

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
        $agenda->setDataInicioAtendimento(new \DateTime('2018-09-10'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-15'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testAgendaSobrepondoDataFinal
     */
    public function testAgendacomDataInicialmenorEDataFinalMaior(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-09-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-12-15'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testAgendaSobrepondoDataFinal
     */
    public function testAgendacomDataInicialmaiorEDataFinalMenor(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-05'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-10'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    public function testPrimeiraAgendaComHorario()
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-30'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('09:00'));
        $agenda->setHorarioFimAtendimento(new \DateTime('12:00'));
        
        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,[]);

        $this->assertTrue($resultado);

        return [$agenda];        
    }

    /**
     * @depends testPrimeiraAgendaComHorario
     */
    public function testInserirAgendaComAsMesmasDatasEHoras(Array $agendas)
    {
        $resultado = $this->agendaValidator->validaDataDisponivel($agendas[0],$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComAsMesmasDatasEHoras
     */
    public function testInserirAgendaComAsMesmasDatasMasHorasDiferentes(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-30'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('14:00'));
        $agenda->setHorarioFimAtendimento(new \DateTime('15:00'));
        $agenda->setId(1);
        
        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertTrue($resultado);
        array_push($agendas,$agenda);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComAsMesmasDatasMasHorasDiferentes
     */
    public function testInserirAgendaComDatasComDatasInicioEFimEntreDatasJaExistenteMasHorasDiferentes(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-05'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-20'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('15:01'));
        $agenda->setHorarioFimAtendimento(new \DateTime('18:00'));
        $agenda->setId(2);
        
        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);
        
        $this->assertTrue($resultado);
        array_push($agendas,$agenda);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComDatasComDatasInicioEFimEntreDatasJaExistenteMasHorasDiferentes
     */
    public function testInserirAgendaComAsMesmasDatasEHoraInicialComConflito(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-30'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('17:00'));
        $agenda->setHorarioFimAtendimento(new \DateTime('22:00'));
        
        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComAsMesmasDatasEHoraInicialComConflito
     */
    public function testInserirAgendaComAsMesmasDatasEHoraFinalComConflito(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-30'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('08:00'));
        $agenda->setHorarioFimAtendimento(new \DateTime('11:00'));
        
        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComAsMesmasDatasEHoraInicialComConflito
     */
    public function testInserirAgendaComAsMesmasDatasEHoraInicialMenorEHoraFinalMaior(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-30'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('08:00'));
        $agenda->setHorarioFimAtendimento(new \DateTime('23:00'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComAsMesmasDatasEHoraInicialMenorEHoraFinalMaior
     */
    public function testInserirAgendaComDataInicioMenorMasDataFimComConflitoEHorariosValidos(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-09-01'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-10-15'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('06:00'));
        $agenda->setHorarioFimAtendimento(new \DateTime('07:00'));
        $agenda->setId(3);

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertTrue($resultado);
        array_push($agendas,$agenda);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComDataInicioMenorMasDataFimComConflitoEHorariosValidos
     */
    public function testInserirAgendaComDataFimMaiorMasDataInicioComConflitoEHorariosValidos(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-10'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-11-15'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('07:01'));
        $agenda->setHorarioFimAtendimento(new \DateTime('08:00'));
        $agenda->setId(4);

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertTrue($resultado);
        array_push($agendas,$agenda);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComDataFimMaiorMasDataInicioComConflitoEHorariosValidos
     */
    public function testInserirAgendaComDatasSobrepostasEComHorarioInicialIgualAUmhorarioFinalJaCadasrtado(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-10'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-11-15'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('18:00'));
        $agenda->setHorarioFimAtendimento(new \DateTime('20:00'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

    /**
     * @depends testInserirAgendaComDatasSobrepostasEComHorarioInicialIgualAUmhorarioFinalJaCadasrtado
     */
    public function testInserirAgendaComDatasSobrepostasEComHorarioFinalIgualAUmhorarioInicialJaCadasrtado(Array $agendas)
    {
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(new \DateTime('2018-10-10'));
        $agenda->setDataFimAtendimento(new \DateTime('2018-11-15'));
        $agenda->setHorarioInicioAtendimento(new \DateTime('06:00'));
        $agenda->setHorarioFimAtendimento(new \DateTime('09:00'));

        $resultado = $this->agendaValidator->validaDataDisponivel($agenda,$agendas);

        $this->assertFalse($resultado);
        return $agendas;
    }

}