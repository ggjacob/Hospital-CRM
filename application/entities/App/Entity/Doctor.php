<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="doctors")
 */
class Doctor extends \App\Entity
{
    /**
     * @OneToOne(targetEntity="User", fetch="LAZY")
     */
    protected $user;
    
    /**
     * @Column(type="string", length=50, nullable=false)
     */
    protected $area;
    
    /**
     * @OneToMany(targetEntity="Meeting", mappedBy="doctor", fetch="LAZY")
     */
    protected $meetings;
    
    public function __construct() {
        $this->meetings = new \Doctrine\Common\Collections\ArrayCollection();
    }
}