<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="resources")
 */
class Resource extends \App\Entity
{
    /**
     * @Column(type="string")
     */
    protected $name;
}