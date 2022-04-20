<?php

use Phalcon\Mvc\Controller;
use App\Components\MyEscaper;

class BlogController extends Controller
{
    public function indexAction()
    {
    }
    public function writeAction()
    {
        $blog = new Blogs();
        $escaper = new MyEscaper();
        if (!$this->request->getPost('file')) {
            $inputdata = array(
                'title' => $escaper->sanitize($this->request->getPost('title')),
                'user_id' => 1,
                'file' => 'null',
                'content' => $escaper->sanitize($this->request->getPost('content')),
            );
        } else {
            $inputdata = array(
                'title' => $escaper->sanitize($this->request->getPost('title')),
                'user_id' => 1,
                'file' => $escaper->sanitize($this->request->getPost('file')),
                'content' => $escaper->sanitize($this->request->getPost('content')),
            );
        }
        $blog->assign(
            $inputdata,
            [
                'blog_id',
                'user_id',
                'title',
                'file',
                'content',

            ]
        );


        $success = $blog->save();
        $this->view->success = $success;

        if ($success) {
            $message = "Thanks for registering!";
            $token =  $this->session->get('token');
            $this->response->redirect('blog/feed?bearer='.$token);
        } else {
            $message = "Sorry, the following problems were generated:<br>"
                . implode('<br>', $blog->getMessages());
        }

        $this->view->message = $message;
    }
    public function feedAction()
    {
        $blog = Blogs::find();
        $this->view->token = $this->session->get('token');
        $this->view->value = $blog;
        $this->view->id = $this->session->get('id');
    }
    public function myfeedAction()
    {
        $this->view->token = $this->session->get('token');
        $id = $this->session->get('id');
        $blog = Blogs::find(["user_id='$id'"]);
        $this->view->value = $blog;
    }
    public function viewAction()
    {
        $this->view->token = $this->session->get('token');
        $id = $_POST['view'];
        if (isset($_POST['view'])) {
            $id = $_POST['view'];
            $this->session->set('view', $id);
        } else {
            $id = $this->session->get('view');
        }
        $blog = Blogs::find(["blog_id='$id'"]);

        $this->view->value = $blog;
        $blog = Blogs::find(['limit' => 3]);

        $this->view->moreBLogs = $blog;
        $like = Likes::find("blog_id='$id'");
        $this->view->likes = count($like);

        $comment = Comments::find("blog_id='$id'");
        $this->view->comment = $comment;
        $this->view->id = $this->session->get('id');
    }
    public function editAction()
    {
        $id = $_POST['view'];
        $blog = Blogs::find(["blog_id='$id'"]);

        $this->view->value = $blog;
    }
}
