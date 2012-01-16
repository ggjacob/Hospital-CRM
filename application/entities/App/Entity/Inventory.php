<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="inventories")
 */
class Inventory extends \App\Entity
{
    /**
     * @Column(type="string", length=255, nullable=false)
     */
    protected $name;
    
    /**
     * @Column(type="integer", nullable=false)
     */
    protected $count;
    
    /**
     * @Column(type="inventoryType", nullable=false)
     */
    protected $type;
    
    /**
     * @ManyToMany(targetEntity="Patient", inversedBy="medicineRequisitions", fetch="LAZY")
     */
    protected $patients;
    
    public function __construct() {
        $this->patients = new \Doctrine\Common\Collections\ArrayCollection();
    }
}