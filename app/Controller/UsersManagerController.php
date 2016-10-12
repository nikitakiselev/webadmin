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
    public $components = array('Paginator');

    public function index()
    {
        $users = $this->User->find('all');

        $this->set('users', $users);
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

                return $this->redirect('/usersManager/index');
            }

            $this->Session->setFlash(__('Unable to update this user.'), 'errors');
        }

        $this->set('user', $user);

        if (!$this->request->data) {
            $this->request->data = $user;
        }
    }
}
