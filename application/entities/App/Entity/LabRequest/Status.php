<?php

namespace App\Entity\LabRequest;

class Status extends \App\Type\Enum
{
    const WAITING = 'WAITING';
    const DELIVERED = 'DELIVERED';

    protected $name = 'labRequestStatus';
    protected $values = array(
        self::WAITING,
        self::DELIVERED
    );
}