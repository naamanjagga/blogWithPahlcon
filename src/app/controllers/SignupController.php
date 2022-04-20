<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;
use App\Handler\EventHandler;
use App\Components\MyEscaper;
use App\Components\Locale;



class SignupController extends Controller
{

        public function indexAction()
        {
                $var = new Locale();
                $this->view->t    = $var->getTranslator();
        }
        public function registerAction()
        {
                $email = $this->request->getPost('email');
                $user = Users::findFirstByemail($email);
                $checkbox = $this->request->getPost('remember-me');
                if ($checkbox == 'on') {
                        if ($user != null) {
                                echo 'Email id already exist';
                                die;
                        } else {

                                $user = new Users();
                                $escaper = new MyEscaper;
                                $inputdata = array(
                                        'name' => $escaper->sanitize($this->request->getPost('name')),
                                        'email' => $escaper->sanitize($this->request->getPost('email')),
                                        'password' => $escaper->sanitize($this->request->getPost('password')),
                                        'role' => 'user',
                                        'status' => 'restricted'

                                );

                                // assign value from the form to $user
                                $user->assign(
                                        $inputdata,
                                        [
                                                'name',
                                                'email',
                                                'password',
                                                'role',
                                                'status'
                                        ]
                                );
                                // Store and check for errors


                                $success = $user->save();

                                // passing the result to the view
                                $this->view->success = $success;

                                if ($success) {
                                        $user = Users::findFirstByemail($email);
                                        $id = $user->id;
                                        $this->session->set("id", $id);
                                        // echo  $this->session->get("id");die;
                                        $message = "Thanks for registering!";
                                        $this->logger2->error('User singed up');

                                        $token = new Eventhandler();
                                        $tokenObject = $token->createToken('user');
                                        $this->session->set('token',$tokenObject);
                                        if ($tokenObject != null) {
                                                $checkToken = 1;
                                        } else {
                                                echo 'access denied';
                                        }
                                        $this->response->redirect('blog/feed?bearer=' . $tokenObject);
                                        // $this->response->redirect('blog/feed?bearer=' . $tokenObject);
                                } else {
                                        $this->logger2->error('Something went wrong');
                                        $message = "Sorry, the following problems were generated:<br>"
                                                . implode('<br>', $user->getMessages());
                                }
                        }
                } else {
                        $message = 'Please agree to our terms and conditions';
                }
                // passing a message to the view
                $this->view->message = $message;
        }
}
