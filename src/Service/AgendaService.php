<?php
namespace App\Service;

use DateTime;
use DateInterval;

class AgendaService{

    /**
     * @param Array $agendas array wit agendas for date
     * @param Array $horarios array with horarios registred for one paciente
     * 
     * @return Array[] Returns an array with hours
     */
    public function horariosDisponiveisArray(Array $agendas, Array $horariosMarcados){
        $horarios = [];
        $horariosFora = [];
        foreach($agendas as $agenda){
            $config = $agenda->getAgendaConfig();

            $horario = $agenda->getHorarioInicioAtendimento();
            $intervalo = new DateInterval('PT'.$config->getDuracaoConsulta().'M');

            for($horario; $horario < $agenda->getHorarioFimAtendimento(); $horario->add($intervalo)){
                if(count($horariosMarcados)>0){
                    foreach($horariosMarcados as $horarioMarcado){
                        $horarioConsulta = $horarioMarcado->getDataConsulta();
                        $buscaHorarios = array_search($horario->format('H:i:s'), $horarios);
                        $buscaHorariosFora = array_search($horario->format('H:i:s'), $horariosFora);
                        
                        if( $horarioConsulta->format('H:i:s') != $horario->format('H:i:s') && $buscaHorariosFora === false )
                        {
                            if(count($horarios) == 0){
                                array_push($horarios,$horario->format('H:i:s'));
                            }elseif( $buscaHorarios === false && $buscaHorariosFora === false){
                                array_push($horarios,$horario->format('H:i:s'));
                            }
                            
                        }else{
                            if( $buscaHorariosFora === false){
                                array_push($horariosFora,$horario->format('H:i:s'));
                            }
                        }

                        $buscaHorarios = array_search($horario->format('H:i:s'), $horarios);
                        $buscaHorariosFora = array_search($horario->format('H:i:s'), $horariosFora);
                        if(is_int($buscaHorarios) && $buscaHorariosFora !== false){
                            unset($horarios[$buscaHorarios]);
                        }
                         
                    }
                }else{
                    if(array_search($horario->format('H:i:s'), $horarios) === false){
                        array_push($horarios,$horario->format('H:i:s'));
                    }
                }
            }
        }
        
        sort($horarios);
        return $horarios;
    }

}