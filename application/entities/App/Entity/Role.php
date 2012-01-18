<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="roles")
 */
class Role extends \App\Entity
{
    /**
     * @Column(type="string")
     */
    protected $name;
    
    /**
     * @ManyToMany(targetEntity="Resource", fetch="LAZY")
     * @JoinTable(name="roles_have_resources", 
     *      joinColumns={@JoinColumn(name="role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="resources_id", referencedColumnName="id")})
     */
    protected $resources;
    
    /**
     * @ManyToMany(targetEntity="User", mappedBy="roles", fetch="LAZY")
     */
    protected $users;
    
    /**
     * Returns the default role name
     *
     * @return string Default role name
     */
    public static function getDefaultRole() {
        return 'Visitor';
    }
    
    /**
     * For performance reasons, the default role's id is returns via this
     * method.
     * 
     * @return int Id of the default role.
     */
    public static function getDefaultRoleId() {
        return 1;
    }
    
    /**
     * For performance reasons, the moderator role's id is returns via this
     * method.
     * 
     * @return int Id of the moderator role.
     */
    public static function getModeratorRoleId() {
        return 3;
    }

    /**
     * Given a role id and a corresponding resource name, search the database
     * entry
     *
     * @param int $roleId
     * @param string $resourceName
     * @return boolean
     */
    public static function isAllowed($roleId, $resourceName) {
        // fetch the Doctrine container.
        $doctrineContainer = \Zend_Registry::get('doctrine');
        
        // fetch the role
        /**
         * @var Role
         */
        $role = $doctrineContainer
                     ->getEntityManager()
                     ->getRepository('App\Entity\Role')
                     ->find($roleId);

        // check wheter the role exists or not
        if (empty($role->id)) {
            return false;
        }
        
        // fetch the record
        $resource = $doctrineContainer
                     ->getEntityManager()
                     ->getRepository('App\Entity\Resource')
                     ->findOneByName($resourceName);

        // make sure that the resource exists
        if (empty($resource) || ! $resource->name) {
            return false;
        }

        // check the resource for the role
        return false !== $role->resources->contains($resource);
    }
}