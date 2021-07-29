<?php
include_once 'Model.php';

class Cart extends Model {
    public string $table = 'carts';
    public string $primary_key = 'id';
    public string $columns = 'id, name, phone, email, address, is_ordered';

    public function add_cart(string $name, string $phone, string $email, string $address, array|string $phones_id, array|string $quantities) {
        //Add a new cart
        $date = date('Y-m-d H:i:s');
        $cart_id = $this->db->insert('carts', 'name, phone, email, address, time, is_ordered', "$name, $phone, $email, $address, $date, 0");
        
        //Add phones to cart
        for ($index = 0; $index < count($phones_id); $index++) {
            $phone_id = $phones_id[$index];
            $quantity = $quantities[$index];
            $phone = $this->db->query("SELECT price, discount, quantity FROM phones WHERE id = '$phone_id'")->fetch_assoc();
            $price = $phone['price'] - ($phone['price'] * $phone['discount'] / 100);
            $new_quantity = $phone['quantity'] - $quantity;
            
            $this->db->insert('carts_phones', 'phone_id, cart_id, quantity, price', "$phone_id, $cart_id, $quantity, $price");
            //$this->db->update('glasses', 'quantity', "$new_quantity", "id = $glass_id");
        }

        return $cart_id;
    }

    public function get_phones($phone_id) : array {
        return $this->db->query("SELECT * FROM phones WHERE id = $phone_id")->fetch_assoc();
    }

    public function get_orders() {
        return $this->db->query('SELECT * FROM carts ORDER BY time DESC');
    }

    public function get_order_cart($cart_id) : array {
        return $this->db->query("SELECT * FROM carts WHERE id = $cart_id")->fetch_assoc();
    }

    public function get_order_phones($cart_id) {
        return $this->db->query("SELECT phones.id AS id, phones.name AS name, brands.name AS brand_name, carts_phones.quantity AS quantity, carts_phones.price AS price FROM phones INNER JOIN carts_phones ON phones.id = carts_phones.phone_id INNER JOIN brands ON brands.id = phones.brand_id WHERE cart_id = $cart_id");
    }

    public function verify_order($id) {
        $carts_phones = $this->db->query("SELECT * FROM carts_phones WHERE cart_id = $id");
        while($cart_phone = $carts_phones->fetch_assoc()) {
            $phone_id = $cart_phone['phone_id'];
            $phone = $this->db->query("SELECT * FROM phones WHERE id = $phone_id")->fetch_assoc();
            $new_quantity = $phone['quantity'] - $cart_phone['quantity'];
            $this->db->update('phones', 'quantity', "$new_quantity", "id = $phone_id");
        }
        $this->db->update('carts', 'is_ordered', '1', "id = $id");
    }
}
//Chưa trừ được bảng glasses