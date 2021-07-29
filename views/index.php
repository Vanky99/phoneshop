<?php
include __DIR__ . '/layouts/header.php';
?>
<style>
    .phone-image {
        height: 300px;
        background-repeat:no-repeat;
        background-size:cover;
        background-position: 50%;
    }

    .hover-image {
        transition: transform .2s;
    }

    .hover-image:hover {
        transform: scale(1.025);
    }
</style>
<!--Phones-->
<div class="row">
    <div class="col-2">
        <!--Searching-->
        <h4 class="text-center">Tìm kiếm</h4>
        <div class="row align-items-center justify-content-center mb-3">
            <div class="col-12">
                <input id="input-search" class="form-control mb-2" name="search" value="<?=$params['keyword']?>" onclick="this.setSelectionRange(0, this.value.length)" />
                <div class="d-flex justify-content-center">
                    <button onclick="search()" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </div>
        </div>
        <!--End of Searching-->
        <!--Brand-->
        <ul class="list-group">
            <li class="list-group-item list-group-item-action active"><h6>Hãng sản xuất</h6></li>
            <?php
            while($brand = $params['brands']->fetch_assoc()) {
                $active = isset($_GET['brand_id']) && $_GET['brand_id'] == $brand['id'];
            ?>
                <a href="index.php?action=phone&method=index&brand_id=<?=$brand['id']?>" class="list-group-item <?=$active ? 'list-group-item-dark' : ''?> list-group-item-action"><?=$brand['name']?></a>
            <?php
            }
            ?>
        </ul>
        <!--End of brand-->
    </div>
    <div class="col-10">
        <?php
        if (isset($_GET['keyword'])) {
        ?>
            <h5>Kết quả tìm kiếm: <?=$_GET['keyword']?></h5>
        <?php
        }
        ?>
        <?php
        if ($params['data']->num_rows > 0) {
        ?>
            <div class="row">
                <?php
                while($phone = $params['data']->fetch_assoc()) {
                ?>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-4">
                    <div class="d-flex flex-column border border-light p-4 text-truncate">
                        <a class="link link-secondary text-decoration-none p-0" href="index.php?action=phone&method=show&id=<?=$phone['id']?>"><h5 class="text-truncate"><?=$phone['name']?></h5></a>
                        <a href="index.php?action=phone&method=show&id=<?=$phone['id']?>" style="background-image: url('<?='public/images/phones/phone' . $phone['id'] . '.png'?>')" class="w-100 phone-image hover-image mb-3" role="button"></a>
                        <h5 class="text-danger"><?=number_format($phone['price'] - ($phone['price'] * $phone['discount'] / 100))?>đ</h5>
                        <div class="text-decoration-line-through"><?=number_format($phone['price'])?>đ</div>
                        <a href="index.php?action=phone&method=show&id=<?=$phone['id']?>" class="btn btn-danger mx-5 mb-3">Xem chi tiết</a>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        <?php
        } else {
        ?>
            Không tìm thấy
        <?php
        }
        ?>
    </div>
</div>
<!--End of phones-->
<script>
    //Add a new phone
    $("#input-search").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            search();
        }
    });

    function search() {
        keyword = $('#input-search').val();
        window.location.href = 'index.php?action=phone&method=index&keyword=' + keyword;
    }
</script>
<!--End of add phone modal-->
<?php
include __DIR__ . '/layouts/footer.php';
?>