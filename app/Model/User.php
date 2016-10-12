<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel
{
    /**
     * Model validation rules
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'rule' => 'notEmpty',
            'required' => true,
        ),
        'type' => array(
            'rule' => 'notEmpty',
        ),
        'email' => array(
            'rule' => 'notEmpty',
        ),
    );

    public function beforeSave($options = array())
    {
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        return true;
    }
}
