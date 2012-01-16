<?php

namespace App\Entity\Patient\In;

class Status extends \App\Type\Enum
{
    const UNDERTRAITMENT = 'UNDERTRAITMENT';
    const DISCHARGED = 'DISCHARGED';

    protected $name = 'inpatientStatus';
    protected $values = array(
        self::UNDERTRAITMENT,
        self::DISCHARGED
    );
}