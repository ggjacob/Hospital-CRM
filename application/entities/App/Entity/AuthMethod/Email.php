<?php

namespace App\Entity\AuthMethod;

// @todo Figure out why the autoloder does not work
// include the interface for the email authentication method
require_once 'SE/Auth/Adapter/Doctrine/Model/Method/Email/Interface.php';

/**
 * @Entity
 * @Table(name="auth_emails")
 */
class Email extends AbstractMethod implements \SE_Auth_Adapter_Doctrine_Model_Method_Email_Interface
{
    /**
     * @Column(type="string", unique=true, nullable=false)
     */
    protected $email;
    
    /**
     * @Column(type="string", nullable=false)
     */
    protected $password;
    
    /**
     * @ManyToOne(targetEntity="\App\Entity\User", inversedBy="emailAuths", fetch="LAZY")
     */
    protected $user;
    
    // Interface methods
    public function fetchEmail() {
        return $this->email;
    }

    // @todo Add error messages
    public function fetchMessage($errorCode) {
        return $errorCode;
    }

    public function fetchName() {
        return "Email Authentication";
    }

    public function fetchPassword() {
        return $this->password;
    }

    public function fetchSlug() {
        return "email-authentication";
    }

    public function fetchStatus() {
        return $this->status;
    }

    /**
     * Given the email address and the password of an email authentication
     * entry, tries to find the corresponding record. If a record is found,
     * it looks for the corresponding user entity, fetches and returns it.
     * 
     * @param String $email Email address of the authentication entry.
     * @param String $password Corresponding password of the email address.
     * @return \App\Entity\User 
     */
    public function fetchUser($email = '', $password = '') {
        /**
         * Fetch the Doctrine container from the registry.
         * 
         * @var \Bisna\Doctrine\Container
         */
        $doctrineContainer = \Zend_Registry::get('doctrine');

        /**
         * Fetch the entity manager.
         * 
         * @var \Doctrine\ORM\EntityManager
         */
        $em = $doctrineContainer->getEntityManager();

        // query the email authentication methods.
        $emailAuth = $em->getRepository('App\Entity\AuthMethod\Email')
                        ->findOneBy(array(
                            'email' => $email,
                            'password' => md5($password)
                        ));

        // return the user.
        if (empty($emailAuth) || empty($emailAuth->user) || 0 == (int) $emailAuth->user->fetchId()) {
            throw new \Exception('User could not be found!');
        }

        return $emailAuth->user;
    }
    
    public static function isUsed($email) {
        /**
         * Fetch the Doctrine container from the registry.
         * 
         * @var \Bisna\Doctrine\Container
         */
        $doctrineContainer = \Zend_Registry::get('doctrine');

        /**
         * Fetch the entity manager.
         * 
         * @var \Doctrine\ORM\EntityManager
         */
        $em = $doctrineContainer->getEntityManager();
        
        // query the email authentication methods.
        $emailAuth = $em->getRepository('App\Entity\AuthMethod\Email')
                        ->findOneBy(array(
                            'email' => $email
                        ));

        if (empty($emailAuth) || 0 == (int) $emailAuth->id) {
            return false;
        } else {
            return true;
        }
    }
}