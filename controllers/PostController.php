<?php
include_once 'Controller.php';
include_once __DIR__ . '/../models/Post.php';
include_once __DIR__ . '/../models/Comment.php';

class PostController extends Controller {
    public function index() {
        if (!$this->authorize())
            return;

        $model_post = new Post();
        $model_comment = new Comment();

        $posts = $model_post->index('*', '', '', 1, 10, 'ORDER BY time DESC');
        $comments = $model_comment->index('comments.id AS id, comments.name AS name, comments.phone AS phone, comments.email AS email, comments.content AS content, comments.time AS time, comments.is_censored AS is_censored, posts.phone_id AS phone_id', 'posts ON posts.id = comments.post_id', '', 1, 10, 'ORDER BY time DESC');

        $this->view('admin-post', ['posts' => $posts, 'comments' => $comments]);
    }

    public function add_comment($request) {
        $get = $request['GET'];
        $post = $request['POST'];

        $model = new Post();
        $model->id = $get['id'];

        $name = isset($post['name']) ? str_replace(',', '&#44;', $post['name']) : '';
        $phone = isset($post['phone']) ? $post['phone'] : '';
        $email = isset($post['email']) ? str_replace(',', '&#44;', $post['email']) : '';
        $content = str_replace(',', '&#44;', $post['content']);

        if ($this->authorize())
            $model->add_admin_comment($this->get_user()['id'], $content);
        else
            $model->add_comment($name, $phone, $email, $content);
        return true;
    }

    public function censore($request) {
        if (!$this->authorize())
            return false;
        
        $get = $request['GET'];

        $model = new Post();
        $model->id = $get['id'];

        $model->censore(true);

        return true;
    }

    public function destroy($request) {
        if (!$this->authorize())
            return false;
        
        $get = $request['GET'];

        $model = new Post();
        $model->id = $get['id'];

        $model->destroy(true);

        return true;
    }
}