<?php

namespace App\Entity\Inventory;

class Type extends \App\Type\Enum
{
    const DRUG = 'DRUG';

    protected $name = 'inventoryType';
    protected $values = array(
        self::DRUG
    );
}