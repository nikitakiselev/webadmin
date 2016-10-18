<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    var $loggedInuser;
    public $components = array('Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'username')
                )
            )
        ), 'Session', 'RequestHandler', 'Paginator', 'Cookie'
    );
    public $helpers = array('Html', 'Form', 'Js');

    public function beforeFilter() {
        $this->Auth->allow();
        $this->Cookie->domain = false;

        App::uses('ConnectionManager', 'Model');
        $dataSource = ConnectionManager::getDataSource('default');
        $host = $dataSource->config['host'];

        $this->loadModel('User');
        $this->loadModel('Subscription');

        if (!defined('webURL')) {
            if ($host == "localhost")
                define('webURL', "http://localhost/proj/30Seconds/");
            else
                define('webURL', "http://54.88.79.236/");
        }

        $this->set($this->loggedInuser, $this->Auth->user());

        $user_id = $this->Auth->user('User.id');
        if ($this->Auth->user('User.type') == 'subscriber') {
            $s = $this->Subscription->find('first', array('conditions' => array('Subscription.user_id' => $user_id, 'stripe_payment_status' => 1), 'order' => array('Subscription.id' => 'DESC')));
            $exp = strtotime('+1 month', strtotime($s['Subscription']['createdt']));
            if ($exp < time()) {
                //$this->Session->destroy();
                $this->Auth->logout();
                // sleep(4);
                $this->Session->write('Stripeid', $user_id);
                $this->Session->setFlash(__('Your subscription plan is expried, please purchage again!'), 'error');
                $this->redirect(array("controller" => "subscribes", "action" => "payment"));
                exit;
            }
        }
        if (isset($user_id)) {
            $this->set('logged_in', $this->Session->read('Auth.User'));
            $type = $this->Auth->user('User.type');
            if ($type == 'facebook') {
                $userInfo['User'] = $this->Auth->user('User.UserObject');
                $userInfo['User']['type'] = $this->Auth->user('User.type');
                $userInfo['User']['username'] = $this->Auth->user('User.name');
                $userInfo['User']['web_user_name'] = $this->Session->read('Auth.User.User.UserObject.web_user_name');
            } else {
                $userInfo = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
            }
            $this->set('userInfo', $userInfo);
        }
    }

    /**
     * Send json response
     *
     * @param $data
     */
    protected function jsonReponse($data)
    {
        $this->autoRender = false;
        $this->response->type('json');
        $json = json_encode($data);
        $this->response->body($json);
    }
}
