<?php

namespace App\Entity\Patient;

/**
 * @Entity
 * @Table(name="patients_out")
 */
class Out extends \App\Entity\Patient
{
    /**
     * @Column(type="text", size=1000, nullable=false)
     */
    protected $complaints;
    
    /**
     * @Column(type="text", size=1000, nullable=true)
     */
    protected $history;
    
    /**
     * @Column(type="text", size=1000, nullable=true)
     */
    protected $diagnosis;
    
    /**
     * @Column(type="text", size=1000, nullable=true)
     */
    protected $advise;
}