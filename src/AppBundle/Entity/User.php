<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=6, options={"default":0})
     */
    protected $loginCount = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $firstLogin;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set loginCount
     *
     * @param integer $loginCount
     *
     * @return User
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount
     *
     * @return integer
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * Set firstLogin
     *
     * @param \DateTime $firstLogin
     *
     * @return User
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    /**
     * Get firstLogin
     *
     * @return \DateTime
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    function getEnabled() {
        return $this->enabled;
    }

    function getLocked() {
        return $this->locked;
    }

    function getExpired() {
        return $this->expired;
    }

    function getExpiresAt() {
        return $this->expiresAt;
    }

    function getCredentialsExpired() {
        return $this->credentialsExpired;
    }

    function getCredentialsExpireAt() {
        return $this->credentialsExpireAt;
    }

    function setSalt($salt) {
        $this->salt = $salt;
    }

    public function setPassword($password) {
        if ($password !== null)
            $this->password = $password;
        return $this;
    }

    public function setRoles(array $roles = array()) {
        $this->roles = array();
        foreach ($roles as $role)
            $this->addRole($role);
        return $this;
    }
}
