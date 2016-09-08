<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
App::uses('AppController', 'Controller');

//For Parse api integration
require ROOT . DS . 'vendor/parse/php-sdk/autoload.php';

use Parse\ParseClient;
use Parse\ParseQuery;
use Parse\ParseObject;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;

$appId = "GxT3Qbs0tWGV8ujcfmtJqwCB9QzYicdmtskmjkRd";
$rest_key = "ovseLVbRwrVvIffkBQ5AsH0ysGwsV3107GQN5ncc";
$master_key = "ktbyBIznia0ErrJT7Kd1rC7H2nCooT0WJdIFbHZO";

ParseClient::initialize($appId, $rest_key, $master_key);
//ParseClient::setServerURL('http://localhost','parse')

@ob_start();
@session_start();

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('User', 'Subscription');
    public $layout = "furniture_dash";

    public function beforeFilter() {
        parent::beforeFilter();
        $userId = $this->Auth->user('User.id');
       

        $action = $this->request->params['action'];
        if (empty($userId) && ($action != "user_login" && $action != "login" && $action != "public_url" && $action != "record_video" && $action != "save_video")) {
            $this->redirect(array("controller" => "users", "action" => "user_login"));
        }
    }

    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     * @throws NotFoundException When the view file could not be found
     * 	or MissingViewException in debug mode.
     */
    public function display() {

        $path = func_get_args();

        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = $title_for_layout = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count - 1])) {
            $title_for_layout = Inflector::humanize($path[$count - 1]);
        }
        $this->set(compact('page', 'subpage', 'title_for_layout'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingViewException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

    public function public_url($web_user_name = '') { //echo $web_user_name;exit;
        $this->layout = "furniture_public";
        if ($web_user_name == 'users') {
            $this->redirect(array("controller" => "users", "action" => "user_login"));
            exit;
        }
        $userObj = new ParseQuery('User_Pitch');
        $userObj->equalTo("primary_pitch", true);
        $userObj->includeKey('user_id');
        // $userObj->descending('createdAt');
        if ($web_user_name != '' && $web_user_name != 'users') {
            $userObj2 = new ParseQuery('_User');
            $userObj2->startsWithReg("web_user_name", $web_user_name);
            $userObjRes2 = $userObj2->find();

            if (count($userObjRes2) > 0) {
                $user = $userObjRes2[0]->getObjectId();
                $userObj->equalTo("user_id", array("__type" => "Pointer", "className" => "_User", "objectId" => $user));
            } else {
                $this->Session->setFlash(__('User name @' . $web_user_name . ' does not exist!'), 'default');
                $this->redirect(array("controller" => "users", "action" => "home"));
            }
        }

        $results = $userObj->find();
        $datalist = array();

        if (!empty($results)) {
            for ($i = 0; $i < count($results); $i++) {
                $object = $results[$i];
                $videoFile = $object->get("pitch_video");
                if (!empty($videoFile)) {
                    $content = $videoFile->getURL();
                    $filename = $videoFile->getName();
                } else {
                    $content = "";
                    $filename = "No Video";
                }

                $user_id = $object->get("user_id");
                $userDetails = $user_id->fetch();
                $user_name = $userDetails->get('name');
                $createdAt = $object->getCreatedAt();
                $createdTime = $createdAt->format('Y-m-d H:i:s');
                $timeDiff = $this->timeDiffCalculate($createdTime);

                $updatedAt = $object->get("updatedAt");

                $object_id = $object->getObjectId();

                $datalist[$i]['objectId'] = $object_id;
                $datalist[$i]['user_id'] = $user_id;
                $datalist[$i]['video_url'] = $content;
                $datalist[$i]['video_name'] = $filename;
                $datalist[$i]['user_name'] = $user_name;
                $datalist[$i]['timeDiff'] = $timeDiff;
            }
        }
        $this->set("datalist", $datalist);
        $this->set("web_user_name", $web_user_name);
        return false;
    }

    public function home() {

        if (!$this->Session->read('Auth.User')) {
            $this->redirect(array("controller" => "users", "action" => "user_login"));
        }
        $type = $this->Auth->user('User.type');
        if ($this->request->is('post') && $type == 'facebook') {
            $web_user_name = $this->data['web_user_name'];
            if ($web_user_name != '') {
                $userObj = new ParseQuery('_User');
                $userObj->equalTo("web_user_name", $web_user_name);
                $userObj->notEqualTo("objectId", $this->Auth->user('User.UserObject.objectId'));
                $userObjRes = $userObj->find();
                if (empty($userObjRes)) {
                    $userObjS = $this->fetchObjectfromId('_User', $this->Auth->user('User.UserObject.objectId'), 'objectId');
                    $userObjS->set("web_user_name", $web_user_name);
                    $userObjS->save(true);
                    $this->Session->write('Auth.User.User.UserObject.web_user_name', $web_user_name);

                    $this->Session->setFlash(__('User name create successfully'), 'default');
                    $this->redirect(array("controller" => "users", "action" => "home"));
                } else {
                    $this->Session->setFlash(__('User name already exist!'), 'default');
                    $this->redirect(array("controller" => "users", "action" => "home"));
                }
            }

            // exit;
        }



        $userObj = new ParseQuery('User_Pitch');
        $userObj->descending("createdAt");
        $userObj->includeKey('user_id');


        if ($type == 'facebook') {
            $user = $this->Auth->user('User.UserObject.objectId');
            // $user = 'UKpZM7KZxB'; /// just remove this line         
            $userObj->equalTo("user_id", array("__type" => "Pointer", "className" => "_User", "objectId" => $user));
        }

        // $userObj->limit(5);
        $results = $userObj->find();

        $datalist = array();

        if (!empty($results)) {
            for ($i = 0; $i < count($results); $i++) {
                $object = $results[$i];
                $videoFile = $object->get("pitch_video");
                if (!empty($videoFile)) {
                    $content = $videoFile->getURL();
                    $filename = $videoFile->getName();
                } else {
                    $content = "";
                    $filename = "No Video";
                }
                $imageFile = $object->get("thumbnail");
                if (!empty($imageFile)) {
                    $image = $imageFile->getURL();
                } else {
                    $image = "";
                }
                $pitch_deck = $object->get('pitch_deck');
                $user_id = $object->get("user_id");
                $userDetails = $user_id->fetch();
                $user_name = $userDetails->get('name');
                $createdAt = $object->getCreatedAt();
                $createdTime = $createdAt->format('Y-m-d H:i:s');
                $timeDiff = $this->timeDiffCalculate($createdTime);

                $updatedAt = $object->get("updatedAt");

                $object_id = $object->getObjectId();

                $datalist[$i]['objectId'] = $object_id;
                $datalist[$i]['user_id'] = $user_id;
                $datalist[$i]['video_url'] = $content;
                $datalist[$i]['thumb'] = $image;
                $datalist[$i]['video_name'] = $filename;
                $datalist[$i]['user_name'] = $user_name;
                $datalist[$i]['timeDiff'] = $timeDiff;
                $datalist[$i]['pitch_deck'] = $pitch_deck;
            }
        }
      
        $this->set('AuthUser', $this->Session->read('Auth.User'));
        $this->set("datalist", $datalist);
        return false;
    }

    public function removePitch() {
        $this->autoRender = false;
        $userId = $this->Auth->user('User.id');
        if (!empty($userId) && $this->request->is('post')) {
            $objectId = $this->data['objId'];

            try {
                $query = new ParseObject('User_Pitch', $objectId);
                $query->destroy();
                echo json_encode(array('status' => 'success', 'msg' => "Data deleted successfully!"));
                exit;
            } catch (ParseException $exp) {
                echo json_encode(array('status' => 'failed', 'msg' => $exp->getMessage()));
                exit;
            }
        } else {
            echo json_encode(array('status' => 'failed', 'msg' => 'Pitch not deleted!'));
            exit;
        }
    }

    public function user_login($user = '') {

        $this->layout = "furniture";
        if ($this->data['User']['username']) {
            //$user = ParseUser::logIn("ash@king.com", "123456");		
            /* $user = ParseUser::logIn($this->data['User']['username'], $this->data['User']['password']);			
              if (!empty($user)) {
              //$this->Auth->login((array) $user);
              $this->Session->write('User.email', $this->data['User']['username']);
              $this->redirect(array("controller" => "users", "action" => "record_video"));
              } else {
              $this->redirect(array("controller" => "users", "action" => "user_login"));
              } */
            try {
                $user = ParseUser::logIn($this->data['User']['username'], $this->data['User']['password']);
                // Do stuff after successful login.
                $this->Session->write('User.email', $this->data['User']['username']);
                $this->redirect(array("controller" => "users", "action" => "record_video"));
            } catch (ParseException $error) {
                // The login failed. Check error to see why.
                $this->redirect(array("controller" => "users", "action" => "user_login"));
            }
        }

        if ($user != '') {
            $userObj = new ParseQuery('_User');
            $userObj->equalTo("objectId", $user);
            $userObjRes = $userObj->find();
            $name = $userObjRes[0]->get('name');
            $arr['email'] = $userObjRes[0]->get('email');
            $arr['profile_url'] = $userObjRes[0]->get('profile_url');
            $arr['objectId'] = $user;
            $arr['web_user_name'] = $userObjRes[0]->get('web_user_name');

            $data = array('User' => array('id' => 2, 'username' => 'admin', 'password' => 'a84646b235fabb73b1de9a0d14b7b8b27cd099f7', 'name' => $name, 'type' => 'facebook', 'UserObject' => $arr));

            $this->Auth->login($data);

            $this->redirect(array("controller" => "users", "action" => "home"));
        }
    }

    public function record_video() {
        $this->layout = "frontend";
        $sUserEmail = $this->Session->read('User.email');
        $this->set("email", $sUserEmail);
    }

    public function save_video() {
        $this->layout = null;
        //echo "<pre>";print_r($_FILES);die("sfsdfsdfsd@@@");
        foreach (array('video', 'audio') as $type) {
            if (isset($_FILES["${type}-blob"])) {

                echo '/var/www/html/app/webroot/img/videos/';

                $fileName = $_POST["${type}-filename"];
                $uploadDirectory = WWW_ROOT . '/img/videos/' . $fileName;

                if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
                    echo(" problem moving uploaded file");
                }

                echo($fileName);
            }
        }
        die;
    }

    /**
     * User login
     */
    public function login() {
        $this->layout = "furniture";

        if ($this->request->is('post')) {
            $password_check = $this->Auth->password($this->data['User']['password']);

            $user = $this->User->find("first", array("conditions" => array("username" => $this->data['User']['username'], "password" => $password_check)));

            if ($user) {
                if ($user['User']['type'] == 'admin') {
                    if ($this->Auth->login($user)) {
                        $this->redirect(array("controller" => "users", "action" => "home"));
                    } else {
                        $this->Session->setFlash(__('Incorrect username or password'), 'error');
                        $this->redirect(array("controller" => "users", "action" => "login"));
                    }
                } else if ($user['User']['type'] == 'subscriber') {
                    $this->loadModel('Subscription');
                    $sub = $this->Subscription->find('first', array('conditions' => array('Subscription.stripe_payment_status' => 1, 'Subscription.user_id' => $user['User']['id'])));
                    if ($sub) {
                        if ($this->Auth->login($sub)) {
                            $this->Session->write('Auth.User.stripe_payment_status', 1);
                            $this->redirect(array("controller" => "users", "action" => "home"));
                        } else {
                            $this->Session->setFlash(__('Incorrect username or password'), 'error');
                            $this->redirect(array("controller" => "users", "action" => "login"));
                        }
                    } else {
                        $this->Session->setFlash(__('Your are not subscribe, Please subscribe first to view videos!'), 'error');
                        $this->Session->write('Stripeid', $user['User']['id']);
                        $this->redirect(array("controller" => "subscribes", "action" => "payment"));
                        exit;
                    }
                } else {
                    $this->Session->setFlash(__('Your account is not active yet'), 'message');
                    $this->redirect(array("controller" => "users", "action" => "login"));
                }
            } else {

                $this->Session->setFlash(__('Incorrect username or password'), 'default');
                $this->redirect(array("controller" => "users", "action" => "login"));
            }
        }
        return false;
    }

    public function logout() {
//$this->set('loggedIn', false);
        $this->Session->destroy();
        $this->Auth->logout();

        $this->redirect(array("controller" => "users", "action" => "user_login"));
    }

    function timeDiffCalculate($timestamp) {
        $datetime1 = new DateTime("now");

        $datetime2 = date_create($timestamp);
        $diff = date_diff($datetime1, $datetime2);
        $timemsg = '';
        if ($diff->y > 0) {
            $timemsg = $diff->y . ' year' . ($diff->y > 1 ? "s" : '');
        } else if ($diff->m > 0) {
            $timemsg = $diff->m . ' month' . ($diff->m > 1 ? "s" : '');
        } else if ($diff->d > 0) {
            $timemsg = $diff->d . ' day' . ($diff->d > 1 ? "s" : '');
        } else if ($diff->h > 0) {
            $timemsg = $diff->h . ' hour' . ($diff->h > 1 ? "s" : '');
        } else if ($diff->i > 0) {
            $timemsg = $diff->i . ' minute' . ($diff->i > 1 ? "s" : '');
        } else if ($diff->s > 0) {
            $timemsg = $diff->s . ' second' . ($diff->s > 1 ? "s" : '');
        }
        if ($timemsg == "") {
            $timemsg = "1 second";
        }

        $timemsg = $timemsg . ' ago';
        return $timemsg;
    }

    function fetchObjectfromId($className, $parentobj, $columnName) {
        $classQury = new ParseQuery($className);
        $classQury->equalTo($columnName, $parentobj);
        $classRes = $classQury->find();

        if (!empty($classRes)) {
            $classobjId = $classRes[0]->getObjectId();
            $classobj = $classQury->get($classobjId);

            return $classobj;
        }

        return false;
    }

    public function check_message() {
        $this->layout = null;
        $this->autoRender = false;

        $chatQry = new ParseQuery('Chat_Parent');
        //$chatQry->equalTo('chat_status', '1');
        $chatQry->containedIn('chat_status', ['1', '2']);
        $result = $chatQry->find();

        if (!empty($result)) {
            $response['status'] = "success";
            for ($i = 0; $i < count($result); $i++) {
                $chatObj = $result[$i];

                $chatId = $result[$i]->getObjectId();
                $chatStatus = $chatObj->get('chat_status');

//                echo "<pre>";
//                print_r($chatObj);
//                exit;
//                if(empty($chatObj))
//                {
//                    echo $chatId;
//                    exit;
//                }
                //fetch 1st chat message
                $chatThreadQry = new ParseQuery('Chat_Messages_Thread');
                $chatThreadQry->equalTo('chat_parent_id', $chatObj);
                $chatdata = $chatThreadQry->find();

//                echo "<pre>";
//                print_r($chatdata);
//                exit;

                if (!empty($chatdata)) {
                    $chat_section = "";

                    if ($chatStatus == '1') {
                        $chatdataObj = $chatdata[0];
                        $chatMessage = $chatdataObj->get('chat_message');
                        $sender_name = $chatdataObj->get('sender_name');
                        $sender_id = $chatdataObj->get('sender_id');
                        $sender_image = $chatdataObj->get('sender_profile_pic');

                        //set it in response array
                        $chat_section = "<div class='messageBox'>"
                                . "<p class='welcome'><img src='$sender_image' id='senderImg" . $i . "' width='20px' height='20px' border='0' title='User Image'/> " . $sender_name . "</p>"
                                . "<div class='msgbox' id='msgbox" . $i . "'><p class='left'>" . $sender_name . ": " . $chatMessage . "</p></div>"
                                . "<form name='message" . $i . "' action=''>"
                                . "<input type='text' name='txtmsg" . $i . "' value='' id='userMessage" . $i . "' style='margin: 8px 0px 0px; border: 1px solid rgb(0, 0, 0); width: 76%;'/> "
                                . "<input type='button' name='btnSend" . $i . "' class='msgSend' value='send' onclick=\"postMsg('" . $i . "')\" style='border: 1px solid; float: right; width: 20%; text-align: center; background: #cddddd none repeat scroll 0% 0%; margin: 7px 1px 0px; cursor: pointer'/>"
                                . "<input type='hidden' name='txtchat" . $i . "' value='" . $chatId . "' id='txtChat" . $i . "'/>"
                                . "<input type='hidden' name='txtsendto" . $i . "' value='" . $sender_id . "' id='txtsendTo" . $i . "'/>"
                                . "<input type='hidden' name='txtreceiver" . $i . "' value='" . $sender_name . "' id='txtreceiver" . $i . "'/>"
                                . "</form>"
                                . "</div>";

                        $response['data'][] = $chat_section;

                        //update the chat status as 2:chat in progress
                        try {
//                            if(isset($chatObj))
//                            {
//                                $chatObj->set('chat_status', '2');
//                                $chatObj->save();
//                            }
//                            else
//                            {
                            //$chatObj2 = new ParseObject("Chat_Parent", trim($chatId));
                            $chatObj2 = $chatQry->get($chatId);
                            $chatObj2->set("chat_status", "2");
                            $chatObj2->save();
//                            }
                        } catch (ParseException $exc) {
                            //$this->Session->setFlash($exc->getMessage());
                            //return false;
                        }
                    } else if ($chatStatus == '2') {
//                        if(isset($chatdataObj))
//                        {
//                            $sender_name = $chatdataObj->get('sender_name');
//                            $sender_id = $chatdataObj->get('sender_id');
//                            $sender_image = $chatdataObj->get('sender_profile_pic');
//                        }
//                        else
//                        {
                        $chatdataObj = $chatdata[0];
                        $sender_name1 = $chatdataObj->get('sender_name');
                        $sender_id = $chatdataObj->get('sender_id');
                        $sender_image = $chatdataObj->get('sender_profile_pic');
//                        }

                        $chat_section .= "<div class='messageBox'>"
                                . "<p class='welcome'><img src='$sender_image' id='senderImg" . $i . "' width='20px' height='20px' border='0' title='User Image'/> " . $sender_name1 . "</p>"
                                . "<div class='msgbox' id='msgbox" . $i . "'>";

                        for ($c = 0; $c < count($chatdata); $c++) {
                            $chatdataObj2 = $chatdata[$c];
                            $chatMessage = $chatdataObj2->get('chat_message');
                            $sender_name = $chatdataObj2->get('sender_name');
//                            $sender_id = $chatdataObj2->get('sender_id');
//                            $sender_image = $chatdataObj2->get('sender_profile_pic');
                            //set it in response array
                            $chat_section .= "<p class='left'>" . $sender_name . ": " . $chatMessage . "</p>";
                        }
                        $chat_section .= "</div><form name='message" . $i . "' action=''>"
                                . "<input type='text' name='txtmsg" . $i . "' value='' id='userMessage" . $i . "' style='margin: 8px 0px 0px; border: 1px solid rgb(0, 0, 0); width: 76%;'/> "
                                . "<input type='button' name='btnSend" . $i . "' class='msgSend' value='send' onclick=\"postMsg('" . $i . "')\" style='border: 1px solid; float: right; width: 20%; text-align: center; background: #cddddd none repeat scroll 0% 0%; margin: 7px 1px 0px; cursor: pointer'/>"
                                . "<input type='hidden' name='txtchat" . $i . "' value='" . $chatId . "' id='txtChat" . $i . "'/>"
                                . "<input type='hidden' name='txtsendto" . $i . "' value='" . $sender_id . "' id='txtsendTo" . $i . "'/>"
                                . "<input type='hidden' name='txtreceiver" . $i . "' value='" . $sender_name1 . "' id='txtreceiver" . $i . "'/>"
                                . "</form>"
                                . "</div>";

                        $response['data'][] = $chat_section;
                    }
                }
            } //Endfor

            echo json_encode($response);
            exit;
        }

        echo json_encode(array("status" => "No new message"));
        exit;
    }

    public function send_message() {
        $this->layout = null;

        if ($this->request->is('post')) {
//            echo "<pre>";
//            print_r($this->data);
//            exit;

            $message = $this->data['message'];
            $receiver_id = $this->data['sendto'];
            $chat_parentId = $this->data['msgthrd'];
            $receiver_name = $this->data['receiver'];
            $receiver_img = $this->data['receiverImg'];

            $adminPic = "http://54.88.79.236/img/avatar5.png";

            $chatObj = new ParseObject('Chat_Parent', $chat_parentId);

            $messageObj = new ParseObject('Chat_Messages_Thread');
            $messageObj->set('chat_parent_id', $chatObj);
            $messageObj->set('sender_id', '1');
            $messageObj->set('sender_name', '30Sec.Pitch');
            $messageObj->set('receiver_id', $receiver_id);
            $messageObj->set('receiver_name', $receiver_name);
            $messageObj->set('chat_message', $message);
            $messageObj->set('sender_profile_pic', $adminPic);
            $messageObj->set('receiver_profile_pic', $receiver_img);
            $messageObj->save();

            $response['status'] = "success";
            $response['data'] = "<p class='left'>30Sec.Pitch: " . $message . "</p>";

            echo json_encode($response);
            exit;
        }

        echo json_encode(array("status" => "error"));
        exit;
    }

    function download() {
        if ($this->request->query('video')) {
            $filename = array_pop(explode("/", $this->request->query('video')));
            header("Content-disposition: attachment; filename=$filename");
            header("Content-type: application/octet-stream");
            readfile($this->request->query('video'));
            setcookie("fileLoading", "true", time() + 3600, "/", "", 0);
            exit;
        } else {
            echo "No Video";
            exit;
        }
    }

}
