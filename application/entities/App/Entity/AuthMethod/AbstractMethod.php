<?php

namespace App\Entity\AuthMethod;

/** @MappedSuperclass */
abstract class AbstractMethod extends \App\Entity
{
    /**
     * @Column(type="string")
     */
    protected $status;
}