<?php
/**
 * Bazzline auth class
 *
 * @author artodeto
 * @since 2012-03-07
 */
class Bazzline_Auth
{
    protected static $_instance = null;
    protected $_auth;

    /**
     * Constructor for class
     *
     * @author artodeto
     * @since 2012-03-08
     */
    protected function __construct() 
    {
        $this->_auth = Zend_Auth::getInstance();
    }

    protected function __clone() {}

    /**
     * Get instance of class
     *
     * @author artodeto
     * @return Bazzline_Auth
     * @since 2012-03-08
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance))
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Log user in (if user is active)
     *
     * @author artodeto
     * @param Application_Model_User $user
     * @return boolean 
     * @since 2012-03-08
     */
    public function logIn(Application_Model_User $user)
    {
        $isLoggedIn = false;

        if ($user->getIsActive())
        {
            $storage = $this->_auth->getStorage();
            $storage->write($user);
            $isLoggedIn = true;
        }

        return $isLoggedIn;
    }

    /**
     * Log user out
     *
     * @author artodeto
     * @return boolean 
     * @since 2012-03-08
     */
    public function logOut()
    {
        $isLoggedOut = false;

        if ($this->_auth->hasIdentity())
        {
            $this->_auth->clearIdentity();
            $isLoggedOut = true;
        }

        return $isLoggedOut;
    }

    /**
     * Validates if user is logged in
     *
     * @author artodeto
     * @return boolean 
     * @since 2012-03-08
     */
    public function isLoggedIn()
    {
        $isLoggedIn = false;

        if ($this->_auth->hasIdentity())
        {
            $isLoggedIn = true;
        }

        return $isLoggedIn;
    }
}