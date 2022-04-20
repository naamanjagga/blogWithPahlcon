<?php

use Phalcon\Mvc\Controller;
use App\Components\MyEscaper;

class EditController extends Controller
{
    public function indexAction()
    {
        $str = $_POST['editBlog'];
        $array = explode(",", $str);
        $this->view->id = $array[0];
        $this->view->title = $array[1];
        $this->view->content = $array[2];
    }
    public function editBlogAction()
    {
        $id = $this->request->getPost('u_id');
        $blog = Blogs::findFirst($id);
        $escaper = new MyEscaper();
        $blog->title = $escaper->sanitize($this->request->getPost('u_title'));
        $blog->content = $escaper->sanitize($this->request->getPost('u_content'));
        if (!$this->request->getPost('file')) {
            $blog->file = 'null';
        } else {
            $blog->file = $escaper->sanitize($this->request->getPost('u_file'));
        }


        $success = $blog->save();
        $this->view->success = $success;

        if ($success) {
            $message = "Thanks for registering!";
            $token =$this->session->get('token');
            $this->response->redirect('blog/feed?bearer='.$token);
        } else {
            $message = "Sorry, the following problems were generated:<br>"
                . implode('<br>', $blog->getMessages());
        }

        $this->view->message = $message;
    }
}
