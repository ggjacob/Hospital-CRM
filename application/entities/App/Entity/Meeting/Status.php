<?php

namespace App\Entity\Meeting;

class Status extends \App\Type\Enum
{
    const FINISHED = 'FINISHED';
    const POSTPONED = 'POSTPONED';
    const RESERVED = 'RESERVED';
    const CANCELLED = 'CANCELLED';
    const ONGOING = 'ONGOING';

    protected $name = 'meetingStatus';
    protected $values = array(
        self::FINISHED,
        self::POSTPONED,
        self::RESERVED,
        self::CANCELLED,
        self::ONGOING
    );
}