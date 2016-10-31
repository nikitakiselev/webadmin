<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

@ob_start();
@session_start();

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
            $password = $this->request->data('password');

            $v = new \App\Support\Validator\Validator($this->request->data, [
                'password' => 'required|confirmed',
            ]);

            $v->addMessage('confirmed', 'Password does not match the confirm password.');

            if ($v->fails()) {
                $_SESSION['errors'] = $v->errors();

                return $this->redirect('/auth/reset/'.$code);
            }

            $this->User->read(null, $user['User']['id']);
            $this->User->saveField('password', $password);
            $this->User->saveField('password_reset_code', null);

            $this->Session->setFlash('Your password successfully changed. Try login here.', 'success');

            return $this->redirect('/admin');
        }

        $this->set('code', $code);
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

        $this->Session->setFlash(
            __('Your change password link sended. Check your email.'), 'success'
        );

        return $this->redirect('/auth/forgot');
    }
}
