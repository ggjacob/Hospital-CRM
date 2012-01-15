<?php

/**
 * ACL library that allows whether one or more roles are allowed to use a
 * specific resource or not.
 *
 * @author Onur Yaman <onuryaman@gmail.com>
 */
class SE_Acl
{
    /**
     * Instance of the current class
     * @var SE_Acl
     */
    private static $instance;

    /**
     * Creates an instance for the class if it's not already done.
     * 
     * @return SE_Acl
     */
    public static function createInstance() {
        // create a new instance only if it's not created before
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Returns the ACL instance.
     * 
     * @return SE_Acl
     */
    public static function getInstance() {
        return self::createInstance();
    }

    /**
     * Given the list of role ids and a resource name, finds out whether the
     * role is allowed to use the resource or not.
     *
     * @param mixed $roleIds
     * @param string $resourceName
     * @return boolean
     */
    public static function isAllowed($roleIds, $resourceName) {
        if (is_array($roleIds)) {
            foreach ($roleIds as $roleId) {
                if (\App\Entity\Role::isAllowed($roleId, $resourceName)) {
                    return true;
                }
            }
            return false;
        } else {
            return \App\Entity\Role::isAllowed($roleIds, $resourceName);
        }
    }

    /**
     * This class uses singleton pattern and has its own factory method. We
     * won't allow instantiating it explicitly.
     */
    private function __construct() {
    }
    
}