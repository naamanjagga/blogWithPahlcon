<?php

use Phalcon\Mvc\Controller;

class LikeController extends Controller
{
    public function indexAction()
    {
    }
    public function likeAction()
    {
        $id = $this->session->get('id');
        $blog_id = $this->request->getPost('like');
        $unlike =  Likes::find(["user_id='$id'"]);
        if ($unlike == null) {

            $like = new Likes();
            $inputdata = array(
                'user_id' => $id,
                'blog_id' => $blog_id
            );

            // assign value from the form to $user
            $like->assign(
                $inputdata,
                [
                    'user_id',
                    'blog_id'
                ]
            );
            $like->save();
        } else {
            $flag = 0;
            foreach ($unlike as $u) {
                if ($u->blog_id == $blog_id) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
                $unlike->delete();
            } else {
                $like = new Likes();
                $inputdata = array(
                    'user_id' => $id,
                    'blog_id' => $blog_id
                );

                // assign value from the form to $user
                $like->assign(
                    $inputdata,
                    [
                        'user_id',
                        'blog_id'
                    ]
                );
                $like->save();
            }
        }
        $token = $this->session->get('token');
        $this->response->redirect('blog/view?bearer=' . $token);
    }
    // public function indexAction()
    // {

    // }
}
