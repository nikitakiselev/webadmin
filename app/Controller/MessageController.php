<?php

use Parse\ParseQuery;
use Parse\ParseClient;
use Parse\ParseObject;
use App\Support\Validator\Validator;

App::uses('AppController', 'Controller');

@ob_start();
@session_start();

class MessageController extends AppController
{
    public $uses = array('User');

    public function __construct($request = null, $response = null)
    {
        parent::__construct($request, $response);

        ParseClient::initialize(env('PARSE_APP_ID'), env('PARSE_REST_KEY'), env('PARSE_MASTER_KEY'));
        ParseClient::setServerURL(env('PARSE_SERVER_URL'), 'parse');
    }

    /**
     * Storing message in database
     */
    public function send()
    {
        if (! $this->request->is('post')) {
            return $this->redirect('/');
        }

        $user = $this->Auth->user('User');

        if ($user['type'] !== 'investor') {
            return $this->jsonReponse([
                'status' => 'error',
                'message' => 'Only investor can do this'
            ]);
        }

        $v = new Validator($this->request->data, [
            'message' => 'required',
            'object_id' => 'required',
        ]);

        $v->addMessage('required', 'Please, enter your message to the textarea.');

        if ($v->fails()) {
            return $this->jsonReponse([
                'status' => 'danger',
                'message' => $this->formatErrors($v->errors()),
            ]);
        }

        $pitchId = $this->request->data('object_id');
        $message = $this->request->data('message');

        $investor = $this->fetchInvestorInformation($user);

        $query = new ParseQuery('User_Pitch');
        $query->includeKey('user_id');
        $pitch = $query->get($pitchId);

        $sender = $investor['username'];
        $receiver = $pitch->get('user_id')->get('name');

        $this->storeMessage($sender, $receiver, $pitchId, $message);

        return $this->jsonReponse([
            'status' => 'success',
            'message' => 'Your message was sent.',
            'message_text' => $this->request->data('message'),
            'message_date' => (new DateTime('now', new DateTimeZone(date_default_timezone_get())))->format('d.m.Y H:i'),
            'color' => $user['color'],
        ]);
    }

    /**
     * Format validation errors
     *
     * @param $errors
     * @return string
     */
    private function formatErrors($errors)
    {
        $result = '<ul>';

        foreach ($errors as $attribute => $error) {
            $result .= "<li><strong>{$attribute}</strong>: {$error}</li>";
        }

        $result .= '</ul>';

        return $result;
    }

    /**
     * Store investor message
     *
     * @param string $sender
     * @param string $receiver
     * @param int $pitchId
     * @param string $message
     */
    protected function storeMessage($sender, $receiver, $pitchId, $message)
    {
        $query = new ParseQuery('User_Pitch');
        $pitch = $query->get($pitchId);

        $object = ParseObject::create('InvestorMessage');
        $object->set('sender', $sender);
        $object->set('receiver', $receiver);
        $object->set('pitch', $pitch);
        $object->set('message', $message);
        $object->save();
    }

    /**
     * Fetch current authenticated user-investor information
     *
     * @param array $user Current auth user information
     * @return array user-investor information
     */
    private function fetchInvestorInformation(array $user)
    {
        $attributes = ['id', 'username', 'email', 'mobile', 'first_name', 'last_name', 'company', 'zipcode'];

        return array_filter($user, function ($attribute) use ($attributes) {
            return in_array($attribute, $attributes);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function load($pitchId)
    {
        $user = $this->Auth->user('User');

        $query = new ParseQuery('User_Pitch');
        $pitch = $query->get($pitchId);

        $senderQuery = new ParseQuery('InvestorMessage');
        $senderQuery->equalTo('sender', $user['username']);

        $receiverQuery = new ParseQuery('InvestorMessage');
        $receiverQuery->equalTo('receiver', $user['username']);

        $mainQuery = ParseQuery::orQueries([$senderQuery, $receiverQuery]);
        $mainQuery->equalTo('pitch', $pitch);
        $messages = $mainQuery->find();

        $messages = array_map(function ($message) use ($user) {
            $date = $message->getCreatedAt()->setTimezone(
                new DateTimeZone(date_default_timezone_get())
            );

            return [
                'date' => $date->format('d.m.Y H:i'),
                'sender' => $message->get('sender'),
                'receiver' => $message->get('receiver'),
                'content' => $message->get('message'),
                'color' => $message->get('sender') === $user['username'] ? $user['color'] : '',
            ];
        }, $messages);

        $this->jsonReponse([
            'messages' => $messages
        ]);
    }
}
