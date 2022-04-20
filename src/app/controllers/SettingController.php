<?php

use Phalcon\Mvc\Controller;

class SettingController extends Controller
{
    public function indexAction()
    {
        $setting = $_POST['setting'];
        if ($setting == 'logout') {
            $this->session->destroy();
            $this->response->redirect('login/index');
        } else {
            $id = $this->session->get('id');
            $blog = Blogs::find(["user_id='$id'"]);
            $blog->delete();
            $user = Users::findFirst($id);
            $user->delete();
            $this->session->destroy();
            $this->response->redirect('signup/index');
        }
    }
}
