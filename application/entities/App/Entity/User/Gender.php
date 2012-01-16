<?php

namespace App\Entity\User;

class Gender extends \App\Type\Enum
{
    
    const MALE = 'MALE';
    const FEMALE = 'FEMALE';

    protected $name = 'userGender';
    protected $values = array(
        null,
        self::MALE,
        self::FEMALE
    );
}