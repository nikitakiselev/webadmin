<?php

use App\Support\Validator\Validator;

App::uses('AppController', 'Controller');

@ob_start();
@session_start();

class MessageController extends AppController
{
    public $uses = array('User', 'Message');

    public function send()
    {
        $v = new Validator($this->request->data, [
            'message' => 'required',
            'object_id' => 'required',
        ]);

        $v->addMessage('required', 'Please, enter your message to the textarea.');

        if ($v->fails()) {
            return $this->sendJson([
                'status' => 'danger',
                'message' => $this->formatErrors($v->errors()),
            ]);
        }

        $user = $this->Auth->user('User');

        // Store message in database
        $this->Message->create();
        $this->Message->save([
            'content' => $this->request->data('message'),
            'object_id' => $this->request->data('object_id'),
            'user_id' => $user['id'],
        ]);

        return $this->sendJson([
            'status' => 'success',
            'message' => 'Your message was sent.',
            'message_text' => $this->request->data('message'),
        ]);
    }

    /**
     * Send json response
     *
     * @param $data
     */
    private function sendJson($data)
    {
        $this->autoRender = false;
        $this->response->type('json');
        $json = json_encode($data);
        $this->response->body($json);
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
}
