<?php

use App\Support\Validator\Validator;

App::uses('AppController', 'Controller');

@ob_start();
@session_start();

class MessageController extends AppController
{
    public function send()
    {
        $v = new Validator($this->request->data, [
            'message' => 'required',
            'object_id' => 'required',
        ]);

        if ($v->fails()) {
            return $this->sendJson([
                'status' => 'error',
                'message' => $v->errors(),
            ]);
        }
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
}
