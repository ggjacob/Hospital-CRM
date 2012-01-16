<?php

namespace App\Entity\Patient;

class Type extends \App\Type\Enum
{
    const INPATIENT = 'INPATIENT';
    const OUTPATIENT = 'OUTPATIENT';

    protected $name = 'patientType';
    protected $values = array(
        self::INPATIENT,
        self::OUTPATIENT
    );
}