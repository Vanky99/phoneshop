<?php
include_once 'Controller.php';
include_once __DIR__ . '/../models/Phone.php';
include_once __DIR__ . '/../models/Post.php';

class PhoneController extends Controller {
    public function index($request) {
        $get = $request['GET'];

        $model = new Phone();

        $is_admin = $this->authorize();

        $keyword = isset($get['keyword']) ? $get['keyword'] : '';
        $where = '';
        if (isset($get['brand_id'])) {
            $brand_id = $get['brand_id'];
            $where .= "brand_id = $brand_id AND ";
        }
        if (isset($get['keyword']));
            $where .= "(phones.name LIKE '%$keyword%' OR brands.name = '%$keyword%')";
        $select = '';
        if ($is_admin)
            $select = 'phones.id AS id, phones.name AS name, brands.id as brand_id, brands.name as brand_name, color, quantity, price, discount, is_opened';
        else 
            $select = 'phones.id AS id, phones.name AS name, price, discount';
        $data = $model->index($select, 'brands ON brands.id = phones.brand_id', $where);
        $brands = $model->get_brands();
        $this->view($is_admin ? 'admin-phone' : 'index', ['data' => $data, 'keyword' => $keyword, 'brands' => $brands]);
    }

    public function show($request) {
        $get = $request['GET'];

        $is_admin = $this->authorize();

        $model = new Phone();
        $model->id = $get['id'];
        $data = $model->show('phones.id AS id, phones.name AS name, brands.id as brand_id, brands.name as brand_name, color, quantity, price, discount, is_opened', 'brands ON brands.id = phones.brand_id');
        $data['rating'] = $model->get_rating();

        $posts_data = [];
        $posts = $model->get_posts($is_admin);
        while($post = $posts->fetch_assoc()) {
            $post_model = new Post();
            $post_model->id = $post['id'];
            $comments = $post_model->get_comments($is_admin);
            array_push($posts_data, ['post' => $post, 'comments' => $comments]);
        }
        $data['posts'] = $posts_data;
        $data['posts_count'] = $model->posts_count();
        $this->view('show', $data);
    }

    public function store($request) {
        if (!$this->authorize())
            return false;
        
        $post = $request['POST'];

        $name = str_replace(',', '&#44;', $post['name']);
        $brand = $post['brand'];
        $color = isset($post['color']) ? substr($post['color'], 1) : 0;
        $quantity = isset($post['quantity']) ? $post['quantity'] : 0;
        $price = isset($post['price']) ? $post['price'] : 0;
        $discount = isset($post['discount']) ? $post['discount'] : 0;
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;
        $is_opened = $post['is_opened'];

        $id = $this->db->insert('phones', 'name, brand_id, color, quantity, price, discount, is_opened', "$name, $brand, $color, $quantity, $price, $discount, $is_opened");
        
        if ($image != null) {
            move_uploaded_file($image['tmp_name'], "public/images/phones/phone$id.png");
        }
        
        return true;
    }

    public function update($request) {
        if (!$this->authorize())
            return false;
        
        $post = $request['POST'];

        $id =  $post['id'];
        $name = str_replace(',', '&#44;', $post['name']);
        $brand = $post['brand'];
        $color = isset($post['color']) ? substr($post['color'], 1) : 0;
        $quantity = isset($post['quantity']) ? $post['quantity'] : 0;
        $price = isset($post['price']) ? $post['price'] : 0;
        $discount = isset($post['discount']) ? $post['discount'] : 0;
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;
        $is_opened = $post['is_opened'];

        $this->db->update('phones', 'name, brand_id, color, quantity, price, discount, is_opened', "$name, $brand, $color, $quantity, $price, $discount, $is_opened", "id = $id");
        
        if ($image != null) {
            move_uploaded_file($image['tmp_name'], "public/images/phones/phone$id.png");
        }

        return true;
    }

    public function destroy($request) {
        if (!$this->authorize())
            return false;
        
        $get = $request['GET'];

        $id = $get['id'];

        $this->db->delete('comments', "id IN (SELECT id FROM comments WHERE post_id IN (SELECT id FROM posts WHERE phone_id = $id))");
        $this->db->delete('posts', "id IN (SELECT id FROM posts WHERE phone_id = $id)");
        $this->db->delete('phones', "id = $id");

        unlink("public/images/phones/phone$id.png");

        return true;
    }

    public function add_post($request) {        
        $get = $request['GET'];
        $post = $request['POST'];

        $model = new Phone();
        $model->id = $get['id'];

        $name = str_replace(',', '&#44;', $post['name']);
        $phone = isset($post['phone']) ? $post['phone'] : '';
        $email = isset($post['email']) ? str_replace(',', '&#44;', $post['email']) : '';
        $rating = $post['rating'];
        $content = str_replace(',', '&#44;', $post['content']);

        $model->add_post($name, $phone, $email, $rating, $content);
        return true;
    }
}