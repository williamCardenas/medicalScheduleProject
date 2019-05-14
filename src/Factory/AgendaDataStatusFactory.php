<?php

namespace App\Factory;

use App\Repository\AgendaDataStatusRepository;
use App\Entit\AgendaDataStatus;

class AgendaDataStatusFactory {
    public static function getAgendaDataStatus(String $status, AgendaDataStatusRepository $agendaDataStatusRepository)
    {
        $agendaDataStatus = $agendaDataStatusRepository->findOneBy(['nome'=>$status]);

        return $agendaDataStatus;
    }
}