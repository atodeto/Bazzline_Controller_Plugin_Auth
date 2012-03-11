<?php
/**
 * Bazzline controller plugin auth
 *
 * @author artodeto
 * @since 2012-03-08
 */
class Bazzline_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    protected $_auth;
    protected $_saveUrlBeforeRedirect;
    protected $_session;

    /**
     * Constructor
     *
     * @author artodeto
     * @since 2012-03-08
     */
    public function __construct()
    {
        $this->_auth = Bazzline_Auth::getInstance();
        $this->_saveUrlBeforeRedirect = (Bazzline_Static_Config::get('plugin.auth.saveUrlBeforeRedirect', 0) == 1) ? true : false;
        $this->_session = new Zend_Session_Namespace(__CLASS__);
    }

    /**
     * Validates if user is logged in
     *
     * @author artodeto
     * @param Zend_Controller_Request_Abstract $request
     * @return type 
     * @since 2012-03-08
     */
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
		//Check if the user is not logged in
        if ($this->_auth->isLoggedIn()) {
            if ($this->_saveUrlBeforeRedirect
                && isset($this->_session->redirectUrl)) {
                $redirectUrl = $this->_session->redirectUrl;
                if (strlen($redirectUrl) > 0) {
                    unset($this->_session->redirectUrl);
                    $this->getResponse()->setRedirect($redirectUrl);
                }
            }
        } else {
            $isRedirectRequired = true;
            $noAuthRequired = Bazzline_Static_Config::get('plugin.auth.noAuthRequired', '');

            if (is_a($noAuthRequired, 'Zend_Config')) {
                $noAuthRequired = $noAuthRequired->toArray();
            }

            if (Bazzline_Static_Validate_Array::isValid(
                    $noAuthRequired, 
                    array(Bazzline_Static_Validate_Array::IS_FILLED => true))) {
                $module = $request->getModuleName();
                $controller = $request->getControllerName();
                $action = $request->getActionName();
                $url = '';
                $url .= (strlen($module) > 0) ? '/' . $module : '';
                $url .= (strlen($controller) > 0) ? '/' . $controller : '';
                $url .= (strlen($action) > 0) ? '/' . $action : '';

                foreach ($noAuthRequired as $noAuthUrl) {
                    if ($url == $noAuthUrl) {
                        $isRedirectRequired = false;
                        break;
                    }
                }
            }

            if ($isRedirectRequired) {
                if ($this->_saveUrlBeforeRedirect
                    && !isset($this->_session->redirectUrl)) {
                    $this->_session->redirectUrl = $request->getRequestUri();
                }
                $loginUrl = Bazzline_Static_Config::get('plugin.auth.loginUrl', '/');

                $this->getResponse()->setRedirect($loginUrl);
//                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
//                $redirector->gotoUrl($url)->redirectAndExist();
            }
        }

        return true;
	}
}