<?php

use Phalcon\Mvc\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
    }
    public function userAction()
    {
        $this->view->token =  $this->session->get('token');
        $user = Users::find();
        $this->view->value = $user;
    }
    public function blogAction()
    {
        $this->view->token =  $this->session->get('token');
        $blog = Blogs::find();
        $this->view->value = $blog;
    }
    public function userDeleteAction()
    {
        $token =  $this->session->get('token');
        $id = $_POST['userDelete'];
        $blog = Blogs::find(["user_id='$id'"]);
        $blog->delete(); 
        $user = Users::findFirst($id);
        $user->delete();
        $this->response->redirect('admin/user?bearer='.$token);
    }
    public function blogDeleteAction()
    {
        $token =  $this->session->get('token');
        $id = $_POST['blogDelete'];
        $blog = Blogs::findFirst($id);
        $blog->delete();
        $this->response->redirect('admin/blog?bearer='.$token);
    }
    public function changeRoleAction()
    {
        $token =  $this->session->get('token');
        $str = $_POST['changeRole'];
        $array = explode(",", $str);
        //  $array[0];die;
        $user = Users::findFirst($array[1]);
        $user->role = $array[0];
        $user->save();
        $this->response->redirect('admin/user?bearer='.$token);
    }
}
