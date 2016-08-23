<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
@ob_start();
@session_start();

/**
 * CakePHP SubscribesController
 * @author pravesh
 */
class SubscribesController extends AppController {

    public $uses = array('User', 'Subscription');
    public $layout = "subscribe";
    public $components = array('Session', 'RequestHandler', 'Paginator', 'Cookie'
    );

    public function index() {

        if ($this->request->is('post')) {
            $data = array();
            $user = $this->User->find("first", array("conditions" => array("username" => $this->data['email'])));

            if (!$user) {
                if ($this->data['email'] == '') {
                    $this->Session->setFlash(__('Please enter valid email'), 'error');
                    $url = Router::url(array("controller" => "subscribes", 'action' => 'index'), true) . '/index';
                    $this->redirect($url);
                    exit;
                }
                $this->User->create();
                $data['User']['username'] = $this->data['email'];
                $data['User']['email'] = $this->data['email'];
                $data['User']['last_name'] = $this->data['last_name'];
                $data['User']['first_name'] = $this->data['first_name'];
                $data['User']['company'] = $this->data['company'];
                $data['User']['mobile'] = $this->data['mobile_number'];
                $data['User']['password'] = $this->data['password'];
                $data['User']['type'] = 'subscriber';
                $data['User']['status'] = '0';
                if ($user = $this->User->save($data)) {
                    $this->Session->write('Stripeid', $user['User']['id']);
                    $this->Session->setFlash(__('Thank you for registration, Please subscribe first to view videos!'), 'error');
                    $this->redirect(array("controller" => "subscribes", "action" => "payment"));
                }
            } else {
                $user1 = $this->User->query(
                        "SELECT User.`id` as more_products , `Subscription`.stripe_payment_status from users as User "
                        . "LEFT JOIN `subscriptions` AS `Subscription` ON (`Subscription`.`user_id` = `User`.`id`) "
                        . " WHERE  User.`id`='{$user['User']['id']}'");

                if ($user1['Subscription']['stripe_payment_status'] == 1) {
                    $this->Session->setFlash(__('Your are already register, Please login!'), 'error');
                    $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));
                } else {
                    $this->Session->write('Stripeid', $user['User']['id']);
                    $this->Session->setFlash(__('Your are already register, Please subscribe first to view videos!'), 'error');
                    $this->redirect(array("controller" => "subscribes", "action" => "payment"));
                }
            }
        }
    }

    function payment() {
        $user = $this->Session->read('Stripeid');

        if (empty($user)) {
            $url = Router::url(array("controller" => "subscribes", 'action' => 'index'), true) . '/index';
            $this->redirect($url);
            exit;
        }
        if ($this->request->is('post')) {
            
        } else {
//            $user = $this->Auth->user();
//            if ($user) {
//                $users = $this->User->query(
//                        "SELECT User.`id` as more_products from users as User "
//                        . "LEFT JOIN `subscriptions` AS `Subscription` ON (`Subscription`.`user_id` = `User`.`id`) "
//                        . " WHERE `Subscription`.`stripe_payment_status` ='1' and User.`id`='{$user['User']['id']}'");
//                if (!$users) {
//                    $this->Session->setFlash(__('Your are already register, Please subscribe first to view videos!'), 'error');
//                    $this->redirect(array("controller" => "subscribes", "action" => "payment"));
//                }
//            }
        }
    }

    function process() {
        if (isset($this->data['stripeToken']) && $this->data['stripeToken'] != '') {
            App::import('Vendor', 'Stripe', array(
                'file' => 'Stripe' . DS . 'lib' . DS . 'Stripe.php')
            );
            if (!class_exists('Stripe')) {
                throw new CakeException('Stripe API library is missing or could not be loaded.');
            }
            $useid = $this->Session->read('Stripeid');
            $readUser = $this->User->find('first', array('conditions' => array('User.id' => $useid)));
            Stripe::setApiKey("sk_test_CBIkBwOmtsRLhD8whNx6HmLV");
            $chargeData = array(
                "amount" => 9900,
                "currency" => "usd",
                "card" => $this->data['stripeToken'],
                "description" => "UserID: {$useid} Name:{$readUser['User']['first_name']} {$readUser['User']['last_name']}"
            );
            //print_R($chargeData);exit;
            try {
                $charge = Stripe_Charge::create($chargeData);
            } catch (Stripe_CardError $e) {
                $body = $e->getJsonBody();
                $err = $body['error'];
                CakeLog::error(
                        'Charge::Stripe_CardError: ' . $err['type'] . ': ' . $err['code'] . ': ' . $err['message'], 'stripe'
                );
                $error = $err['message'];
            } catch (Stripe_InvalidRequestError $e) {
                $body = $e->getJsonBody();
                $err = $body['error'];
                CakeLog::error(
                        'Charge::Stripe_InvalidRequestError: ' . $err['type'] . ': ' . $err['message'], 'stripe'
                );
                $error = $err['message'];
            } catch (Stripe_AuthenticationError $e) {
                CakeLog::error('Charge::Stripe_AuthenticationError: API key rejected!', 'stripe');
                $error = 'Payment processor API key error.';
            } catch (Stripe_ApiConnectionError $e) {
                CakeLog::error('Charge::Stripe_ApiConnectionError: Stripe could not be reached.', 'stripe');
                $error = 'Network communication with payment processor failed, try again later';
            } catch (Stripe_Error $e) {
                CakeLog::error('Charge::Stripe_Error: Stripe could be down.', 'stripe');
                $error = 'Payment processor error, try again later.';
            } catch (Exception $e) {
                CakeLog::error('Charge::Exception: Unknown error.', 'stripe');
                $error = 'There was an error, try again later.';
            }

            if ($error !== null) {
                $this->Session->setFlash($error, 'error');
                $this->redirect(array("controller" => "subscribes", "action" => "payment"));
            } else {
                $data = $charge->__toArray(true);
                $user = $this->Session->read('Stripeid');
                if ($user) {
                    $this->Subscription->create();
                    $array['Subscription']['user_id'] = $user;
                    $array['Subscription']['stripe_charge'] = $data['id'];
                    $array['Subscription']['amount'] = $data['amount'];
                    $array['Subscription']['stripe_transaction'] = $data['balance_transaction'];
                    $array['Subscription']['stripe_payment_status'] = $data['paid'];
                    if ($this->Subscription->save($array)) {
                        $u1['User']['id'] = $user;
                        $u1['User']['status'] = 1;
                        $u1['User']['zipcode'] = $this->data['zipcode'];
                        $this->User->save($u1);
                        $u = $this->User->read(null, $user);
                        $this->Auth->login($u);
                        $this->Session->write('Auth.User.stripe_payment_status', 1);
                        $this->Session->setFlash(__('Thanks you for payment'), 'success');
                        $this->redirect(array("controller" => "users", "action" => "home"));
                    }
                }
            }
        } else {
            $this->Session->setFlash(__('Stripe token is invalid'), 'error');
            $this->redirect(array("controller" => "subscribes", "action" => "payment"));
        }
        exit;
    }

}
