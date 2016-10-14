<?php

App::uses('AppController', 'Controller');

@ob_start();
@session_start();

class UsersManagerController extends AppController
{
    /**
     * @var array
     */
    public $uses = array(
        'User',
    );

    /**
     * @var string
     */
    public $layout = "furniture_dash";

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        'Paginator'
    );

    /**
     * Paginator options
     *
     * @var array
     */
    public $paginate = array(
        'limit' => 20,
        'order' => array(
            'User.id' => 'desc'
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        $user = $this->Auth->user('User');

        if (! $user || $user['type'] !== 'admin') {
            return $this->redirect(array("controller" => "admin"));
        }
    }

    /**
     * Index page
     */
    public function index()
    {
        $this->Paginator->settings = $this->paginate;

        $users = $this->Paginator->paginate('User');

        $this->set('users', $users);
    }

    /**
     * Create new user
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->User->create();

            if ($this->User->save($this->request->data)) {

                $this->Session->setFlash(__('New user was created.'), 'success');

                $this->redirect([
                    'action' => 'index',
                ]);
            }

            $this->Session->setFlash(
                __('The user could not be created. Please, try again.', 'error')
            );
        }
    }

    /**
     * @param null $id
     */
    public function edit($id = null)
    {
        if (! $id) {
            throw new NotFoundException(__('Invalid user'));
        }

        $user = $this->User->findById($id);

        if (! $user) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $id;

            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('User has been updated!'), 'success');

                return $this->redirect([
                    'action' => 'index'
                ]);
            }

            $this->Session->setFlash(__('Unable to update this user.'), 'errors');
        }

        $this->set('user', $user);

        if (!$this->request->data) {
            $this->request->data = $user;
        }
    }

    /**
     * Change user password
     *
     * @param null $id
     */
    public function changePassword($id = null)
    {
        if ($this->request->is('post')) {
            $v = new \App\Support\Validator\Validator($this->request->data, [
                'new_password' => 'required|confirmed|password',
            ]);

            if ($v->fails()) {
                $_SESSION['errors'] = $v->errors();
                $this->Session->setFlash('There are some validation errors', 'error');

                return $this->redirect($this->referer());
            }

            $user = $this->User->findById($id);

            if (! $user) {
                throw new NotFoundException(__('Invalid user'));
            }

            $this->User->id = $id;

            if ($this->User->saveField('password', $this->request->data('new_password'))) {
                $this->Session->setFlash(__('The user password has been updated'), 'success');

                return $this->redirect($this->referer());
            }

            $this->Session->setFlash(
                __('The user password could not be updated. Please, try again.', 'error')
            );
        }

        return $this->redirect($this->referer());
    }

    /**
     * Delete user
     *
     * @param $id
     */
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->User->delete($id)) {
            $this->Session->setFlash(
                __('The user with id: %s has been deleted.', h($id)), 'success'
            );
        } else {
            $this->Session->setFlash(
                __('The user with id: %s could not be deleted.', h($id)), 'error'
            );
        }

        return $this->redirect(array('action' => 'index'));
    }
}
