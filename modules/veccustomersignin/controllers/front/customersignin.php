<?php

class VecCustomerSigninCustomerSigninModuleFrontController extends ModuleFrontController
{
	/**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        $this->php_self = 'customersignin';

        if (Tools::getValue('ajax')) {
            return;
        }
        parent::initContent();
    }

	public function displayAjax()
    {
        // Add or remove product with Ajax
        $context = Context::getContext();
        $action = Tools::getValue('action');

        $array_result = array();
        $errors = array();
        $success = array();
        //Customer login
        if ($action == 'customer-login') {
            //check validate
            if (!($email = trim(Tools::getValue('email'))) || !Validate::isEmail($email)) {
                $errors[] = $this->l('Invalid email address');
            }
            if (!($pass = trim(Tools::getValue('password'))) || !Validate::isPasswd($pass)) {
                $errors[] = $this->l('Invalid password');
            }
            if (!count($errors)) {
                Hook::exec('actionAuthenticationBefore');

                //Check email exist
                $customer = new Customer();
                $authentication = $customer->getByEmail($email, $pass);

                if (isset($authentication->active) && !$authentication->active) {
                    $errors[] = $this->l('Your account isn\'t available at this time, please contact us');
                } elseif (!$authentication || !$customer->id || $customer->is_guest) {
                    $errors[] = $this->l('Your email or password is incorrect.');
                } else {
                    //Update cookie to login
                    $this->context->updateCustomer($customer);

                    Hook::exec('actionAuthentication', array('customer' => $this->context->customer));

                    // Login information have changed, so we check if the cart rules still apply
                    CartRule::autoRemoveFromCart($this->context);
                    CartRule::autoAddToCart($this->context);
                    $success[] = $this->l('You have successfully logged in');
                }
            }
        }
        //Reset password
        if ($action == 'reset-pass') {
            //Check validate
            if (!($email = trim(Tools::getValue('email'))) || !Validate::isEmail($email)) {
                $errors[] = $this->l('Invalid email address');
            } else {
                //Check email exist
                $customer = new Customer();
                $customer->getByEmail($email);
                if (is_null($customer->email)) {
                    $customer->email = $email;
                }

                if (!$customer->active) {
                    $errors[] = $this->l('You cannot regenerate the password for this account.');
                } elseif ((strtotime($customer->last_passwd_gen . '+' . ($minTime = (int) Configuration::get('PS_PASSWD_TIME_FRONT')) . ' minutes') - time()) > 0) {
                    $errors[] = $this->l('You can regenerate your password only every ') . (int) $minTime . $this->l(' minute(s)');
                } else {
                    if (!$customer->hasRecentResetPasswordToken()) {
                        $customer->stampResetPasswordToken();
                        $customer->update();
                    }

                    //Send mail to reset password
                    $mailParams = array(
                        '{email}' => $customer->email,
                        '{lastname}' => $customer->lastname,
                        '{firstname}' => $customer->firstname,
                        '{url}' => $this->context->link->getPageLink('password', true, null, 'token=' . $customer->secure_key . '&id_customer=' . (int) $customer->id . '&reset_token=' . $customer->reset_password_token),
                    );

                    Mail::Send($this->context->language->id, 'password_query', $this->l('Password query confirmation'), $mailParams, $customer->email, $customer->firstname . ' ' . $customer->lastname);
                    $success[] = $this->l('If this email address has been registered in our shop, you will receive a link to reset your password at ');
                    
                }
            }
        }
        $array_result['success'] = $success;
        $array_result['errors'] = $errors;
        die(Tools::jsonEncode($array_result));
    }

}