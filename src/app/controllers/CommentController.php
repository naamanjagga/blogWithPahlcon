<?php

use Phalcon\Mvc\Controller;

class CommentController extends Controller
{
    public function indexAction()
    {
    }
    public function commentAction()
    {
        $id = $this->session->get('id');
        $blog_id = $this->request->getPost('btn_comment');
        $comment = $this->request->getPost('comment');

        $like = new Comments();
        $inputdata = array(
            'user_id' => $id,
            'blog_id' => $blog_id,
            'comment' => $comment
        );

        // assign value from the form to $user
        $like->assign(
            $inputdata,
            [
                'user_id',
                'blog_id',
                'comment'
            ]
        );
        $like->save();
        $token =$this->session->get('token');
        $this->response->redirect('blog/view?bearer='.$token);
    }
    public function commentDeleteAction()
    {
        if (isset($_POST['commentDelete'])) {
            $id = $_POST['commentDelete'];
            $comment = Comments::find(["comment_id='$id'"]);
            $comment->delete();
        }
        $token =$this->session->get('token');
        $this->response->redirect('blog/view?bearer='.$token);
    }
}
