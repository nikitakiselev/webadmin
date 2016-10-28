<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AuthController extends Controller
{
    /**
     * @var array
     */
    public $uses = ['User'];

    /**
     * @var string
     */
    public $layout = "furniture";

    /*
     * Forgot password page
     */
    public function forgot()
    {
        if ($this->request->is('post')) {
            $email = $this->request->data('email');

            if (! $email || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new Exception('Wrong email address.');
            }

            $user = $this->User->find('first', [
                'conditions' => [
                    'User.email' => $email
                ]
            ]);

            if (! $user) {
                // no this user
            }

            $password_reset_code = uniqid();

            $this->User->read(null, $user['User']['id']);
            $this->User->saveField('password_reset_code', $password_reset_code);

            $this->sendPasswordResetLink($email, $password_reset_code);
        }
    }

    public function reset($code = '')
    {
        if (! $code) {
            throw new NotFoundException(__('Invalid password reset link'));
        }

        $user = $this->User->find('first', [
            'conditions' => [
                'User.password_reset_code' => $code
            ]
        ]);

        if (! $user) {
            throw new NotFoundException(__('This password reset link is outdated.'));
        }

        if ($this->request->is('post')) {
            $password = $this->request->data('new_password');

            // validate input password

            $this->User->read(null, $user['User']['id']);
            $this->User->saveField('password', $password);
        }
    }

    /**
     * Send password reset link to user's email
     *
     * @param $email
     * @param $code
     */
    protected function sendPasswordResetLink($email, $code)
    {
        $mail = new CakeEmail('default');
        $mail->template('password-reset')
            ->emailFormat('html')
            ->viewVars(compact('code'))
            ->to($email)
            ->subject('Password reset link from 30 Second Pitch')
            ->send();
    }
}
