<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="logs")
 */
class Log extends \App\Entity
{
    /**
     * @ManyToOne(targetEntity="Patient", inversedBy="logs", fetch="LAZY")
     */
    protected $patient;
    
    /**
     * @Column(type="string", length=1000, nullable=false)
     */
    protected $message;
}