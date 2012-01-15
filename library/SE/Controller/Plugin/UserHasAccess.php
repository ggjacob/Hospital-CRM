<?php
/**
 * @TODO Documentation
 * @author Onur Yaman <onuryaman@gmail.com>
 */
class SE_Controller_Plugin_UserHasAccess extends Zend_Controller_Plugin_Abstract
{
    /**
     * @TODO Documentation
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $roleIds = null;

        if (! Zend_Auth::getInstance()->hasIdentity()) {
            // by default, the user has the "guest" role and for performance
            // reasons, we say 1 here instead of using Doctrine for fetching
            // the corresponding role's id.
            $roleIds = 1;
        } else {
            // get the entity manager instance.
            $em = Zend_Registry::get('doctrine')->getEntityManager();
            
            // fetch the corresponding user
            $user = $em->getRepository('App\Entity\User')->find(
                Zend_Auth::getInstance()->getIdentity()->id
            );

            // if the user is not found, there's a problem
            if (empty($user->id)) {
                throw new Exception('Fatal error! User cannot be found!');
            }

            // fetch users' roles
            $roleIds = array();
            foreach ($user->roles as $role) {
                $roleIds[] = $role->id;
            }
        }

        // controller wise control
        $resourceName = $request->getParam('module', 'default') . '.' . $request->getParam('controller', 'index') . '.' . $request->getParam('action', 'index');
        require_once 'SE/Acl.php';
        if (! SE_Acl::getInstance()->isAllowed($roleIds, $resourceName)) {
            throw new Exception('You\'re not allowed to see this page!');
        }
    }
}