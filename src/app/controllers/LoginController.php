<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;
use App\Handler\EventHandler;




class LoginController extends Controller
{
    public function indexAction()
    {
        // if ($this->cookies->has("login-action")) {
        //     $role = $this->cookies->get("login-action");
        //     $token = new EventHandler();
        //      $tokenObject = $token->createToken($role);
        //     if ($tokenObject != null) {
        //         $checkToken = 1;
        //     } else {
        //         echo 'access denied';
        //         die;
        //     }
        //     $this->session->set('token',$tokenObject);
        //     $this->response->redirect('blog/feed?bearer=' . $tokenObject);
        // }
    }

    public function loginAction()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = Users::findFirstByemail($email);
        if ($user != null) {
            if ($user->password == $password) {
                $id = $user->id;

                $rem = $this->request->getPost('remember-me');
                if ($rem == 'on') {
                    $this->cookies->set(
                        "login-action",
                        $user->role,
                        time() + 15 * 86400
                    );
                }
                $this->session->set("id", $id);
                if ($this->session->has("id")) {
                    $name = $this->session->get("id");
                }
                $role = $user->role;
                $token = new EventHandler();
                $tokenObject = $token->createToken($role);
                if ($tokenObject != null) {
                    $checkToken = 1;
                } else {
                    echo 'access denied';
                    die;
                }
                $this->view->tokenObject = $tokenObject;
                $this->session->set('token',$tokenObject);
                $this->response->redirect('blog/feed?bearer=' . $tokenObject);
                $this->logger1->info('logged in');
            } else {
                echo ('Something went wrong');
                $this->logger1->error('Something went wrong');
                die();
            }
        } else {
            echo ('email not found');
            $this->logger1->error('wrong email');
            die();
        }
    }

    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect('index/index');
    }
}
