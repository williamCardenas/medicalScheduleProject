<?php
namespace App\Tests\Service;

use App\Entity\Agenda;
use App\Entity\AgendaConfig;
use App\Entity\AgendaData;
use App\Service\AgendaService;

use PHPUnit\Framework\TestCase;

use DateTime;

class AgendaServiceTest extends TestCase
{
    private $agendaService;
    private $horarios = [];

    protected function setUp(): void
    {
        $this->agendaService = new AgendaService();

    }

    private function novaAgenda(DateTime $date): Agenda
    {
        $horario = new DateTime();
        $horario->setDate(0,0,0);
        $agenda = new Agenda();
        $agenda->setDataInicioAtendimento(clone($date));
        $agenda->setDataFimAtendimento(clone($date));

        $horario->setTime(9,0);
        $agenda->setHorarioInicioAtendimento(clone($horario));
        $horario->setTime(12,0);
        $agenda->setHorarioFimAtendimento(clone($horario));
        
        $agendaConfig = new AgendaConfig();
        $agenda->setAgendaConfig($agendaConfig);
        return $agenda;
    }

    private function novoHorarioMarcado(DateTime $dataConsulta): AgendaData
    {
        $agendaData = new AgendaData();
        $agendaData->setDataConsulta($dataConsulta);

        return $agendaData;
    }

    public function testDataSemAgendamento(): Array
    {
        $agendas[] = $this->novaAgenda( new DateTime());

        $horariosDisponiveis = $this->agendaService->horariosDisponiveisArray($agendas,$this->horarios);
        $this->assertEquals(6,count($horariosDisponiveis));

        return $agendas;
    }

    public function testDataComUmAgendamento(): Array
    {
        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(10,0);

        $agendas[] = $this->novaAgenda( new DateTime());
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $horariosDisponiveis = $this->agendaService->horariosDisponiveisArray($agendas,$this->horarios);
        $this->assertEquals(5,count($horariosDisponiveis));
        
        return $agendas;
    }

    public function testDataComDoisAgendamentos(): Array
    {
        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(10,0);

        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));


        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(10,30);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $agendas[] = $this->novaAgenda( new DateTime());

        $horariosDisponiveis = $this->agendaService->horariosDisponiveisArray($agendas,$this->horarios);
        
        $this->assertEquals(4,count($horariosDisponiveis));

        return $agendas;
    }

    public function testDataComTresAgendamentos(): Array
    {
        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(9,0);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(10,0);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));


        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(10,30);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $agendas[] = $this->novaAgenda( new DateTime());

        $horariosDisponiveis = $this->agendaService->horariosDisponiveisArray($agendas,$this->horarios);
        
        $this->assertEquals(3,count($horariosDisponiveis));

        return $agendas;
    }

    public function testDataComtodosHorariosAgendamentos(): Array
    {
        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(9,0);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(9,30);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(10,0);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(10,30);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(11,0);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $dataHoraMarcada = new DateTime();
        $dataHoraMarcada->setTime(11,30);
        array_push($this->horarios, $this->novoHorarioMarcado($dataHoraMarcada));

        $agendas[] = $this->novaAgenda( new DateTime());

        $horariosDisponiveis = $this->agendaService->horariosDisponiveisArray($agendas,$this->horarios);
        
        $this->assertEquals(0,count($horariosDisponiveis));

        return $agendas;
    }
}