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
            'notEmpty' => [
                'rule' => 'notEmpty',
                'required' => true,
            ],
            'is_unique' => [
                'rule' => 'isUnique',
                'message' => 'This username has already been taken.',
            ],
        ),
        'type' => array(
            'rule' => 'notEmpty',
        ),
        'email' => array(
            'required' => [
                'rule' => 'notEmpty',
            ],
            'is_email' => [
                'rule' => array('email', true),
                'message' => 'Please supply a valid email address.'
            ],
            'is_unique' => [
                'rule' => 'isUnique',
                'message' => 'This email has already been taken.',
            ]
        ),
        'first_name' => [
            'required' => [
                'rule' => 'notEmpty',
            ]
        ],
        'last_name' => [
            'required' => [
                'rule' => 'notEmpty',
            ]
        ],
        'password' => [
            'required' => [
                'rule' => 'notEmpty'
            ],
            'confirmed' => [
                'rule' => 'confirmed',
                'message' => 'Please, confirm new password'
            ]
        ]
    );

    /**
     * @param array $options
     * @return bool
     */
    public function beforeSave($options = array())
    {
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }

        return true;
    }

    /**
     * Password confirmation rule
     *
     * @return bool
     */
    public function confirmed()
    {
        return $this->data['User']['password'] === $this->data['User']['password_confirmation'];
    }
}
