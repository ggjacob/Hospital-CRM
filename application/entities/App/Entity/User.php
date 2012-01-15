<?php

namespace App\Entity;

// @todo Figure out why the autoloder does not work
// include the interface for the email authentication method
require_once 'SE/Auth/Adapter/Doctrine/Model/User/Interface.php';

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="users")
 */
class User extends \App\Entity implements \SE_Auth_Adapter_Doctrine_Model_User_Interface
{
    /**
     * @Column(type="string", length=50, nullable=true)
     */
    protected $name;
    
    /**
     * @OneToMany(targetEntity="\App\Entity\AuthMethod\Email", mappedBy="user", fetch="LAZY")
     */
    protected $emailAuths;
    
    /**
     * @ManyToMany(targetEntity="Role", inversedBy="users", fetch="LAZY", cascade={"persist"})
     * @JoinTable(name="users_have_roles", 
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id")})
     */
    protected $roles;
    
    public function __construct() {
        $this->emailAuths = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }
    
    public function fetchId() {
        return $this->id;
    }
    
    /**
     * Given the email and password pair, tries to create a new moderator and
     * his email auth record.
     * 
     * @param string $email
     * @param string $password
     * @return User 
     */
    public static function register($data) {
        // create a new email authmethod instance for email/password pair.
        $emailAuth = new AuthMethod\Email();
        $emailAuth->email = $data['email'];
        $emailAuth->password = md5($data['password']);
        $emailAuth->createdAt = $emailAuth->updatedAt = new \DateTime("now");
        
        // fetch the entity manager.
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        // create a new user.
        $user = new User();
        $user->name = $data['name'];
        $user->emailAuths->add($emailAuth);
        $user->roles->add($em->getRepository('App\Entity\Role')->find(
            Role::getModeratorRoleId()
        ));
        $user->createdAt = $user->updatedAt = new \DateTime("now");
        $emailAuth->user = $user;

        // save the email authmethod instance.
        $em->persist($user);
        $em->persist($emailAuth);
        $em->flush();
        
        // return the corresponding user instance.
        return $user;
    }
}