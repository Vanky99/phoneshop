<?php
include __DIR__ . '/layouts/header.php';
$cart = $params['cart'];
?>
<style>
    .phone-image {
        width: 150px;
        height: 150px;
        background-repeat:no-repeat;
        background-size:cover;
        background-position: 50%;
    }
</style>
<!--Container-->
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-12 p-4">
        <h1 class="text-center">Chi tiết đơn đặt hàng</h1>
        <div class="row">
            <div class="col">
                <div>Mã hóa đơn: <?=$cart['id']?></div>
                <div>Tên khách hàng: <?=$cart['name']?></div>
                <div>Số điện thoại: <?=$cart['phone']?></div>
                <div>Địa chỉ: <?=$cart['address']?></div>
                <div>Email: <?=$cart['email']?></div>
                <div>Ngày đặt hóa đơn: <?=$cart['time']?></div>
            </div>
        </div>
        <!--Carts-->
        <div class="my-4">
            <!--Cart-->
            <?php
            foreach ($params['phones'] as $phone) {
            ?>
                <div class="row m-0 p-2 align-items-center">
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div style="background-image: url('<?='public/images/phones/phone' . $phone['id']. '.png'?>')" class="phone-image"></div>
                            <div class="m-3">
                                <h4><?=$phone['name']?></h4>
                                <h5><?=$phone['brand_name']?></h5>
                                <h5><?=number_format($phone['price'])?>đ</h5>
                                <h6>Số lượng: <?=$phone['quantity']?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                //$index++;
            }
            ?>
        </div>
        <?php
        if (!$cart['is_ordered']) {
        ?>
            <div class="d-flex justify-content-center">
                <button onclick="verifyOrder(<?=$cart['id']?>)" class="btn btn-primary"><h4>Xác nhận đơn đặt hàng</h4></button>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<script>
    function verifyOrder(id) {
        $.get('index.php?action=cart&method=verify_order&id=' + id).done(function(data) {
            if (data) {
                window.location.href = 'index.php?action=cart&method=view_orders';
            }
        });
    }
</script>
<?php
include __DIR__ . '/layouts/footer.php';
?>