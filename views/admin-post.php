<?php
include __DIR__ . '/layouts/header.php';
include_once __DIR__ . '/../functions.php';
?>
<style>
    .star-color {
        color: #ffe921;
    }
</style>
<h1 class="text-center">Đánh giá và bình luận</h1>
<h3 class="text-center">Đánh giá</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <td class="text-center align-middle">Mã đánh giá</td>
            <td class="text-center align-middle">Tên</td>
            <td class="text-center align-middle">Số điện thoại</td>
            <td class="text-center align-middle">Email</td>
            <td class="text-center align-middle" width="200px">Nội dung</td>
            <td class="text-center align-middle">Đánh giá</td>
            <td class="text-center align-middle">Thời gian</td>
            <td class="text-center align-middle">Trạng thái</td>
            <td class="text-center align-middle"></td>
            <td class="text-center align-middle"></td>
        </tr>
    </thead>
    <tbody>
        <?php
        while($posts = $params['posts']->fetch_assoc()) {
        ?>
            <tr>
                <td class="text-center align-middle"><?=$posts['id']?></td>
                <td class="text-center align-middle"><?=$posts['name']?></td>
                <td class="text-center align-middle"><?=$posts['phone']?></td>
                <td class="text-center align-middle"><?=$posts['email']?></td>
                <td class="text-center align-middle"><?=$posts['content']?></td>
                <td class="text-center align-middle">
                    <?php
                    for ($index = 1; $index <= 5; $index++) {
                    ?>
                        <i class="star-color bi <?=$index <= $posts['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                    <?php
                    }
                    ?>
                </td>
                <td class="text-center align-middle"><?=time_elapsed_string($posts['time'])?></td>
                <?php
                if ($posts['is_censored']) {
                ?>
                    <td class="text-center align-middle text-success">Đã được kiểm duyệt</td>
                    <td class="text-center align-middle"><a role="button" onclick="destroyPost(<?=$posts['id']?>)" class="link-dark"><i class="bi bi-trash"></i></a></td>
                <?php
                } else {
                ?>
                    <td class="text-center align-middle text-danger">Đang chờ kiểm duyệt</td>
                    <td class="text-center align-middle">
                        <a role="button" onclick="censorePost(<?=$posts['id']?>)" class="link-dark"><i class="bi bi-check-lg"></i></a>
                        <a role="button" onclick="destroyPost(<?=$posts['id']?>)" class="link-dark"><i class="bi bi-trash"></i></a>
                    </td>
                <?php
                }
                ?>
                <td class="text-center align-middle"><a href="index.php?action=phone&method=show&id=<?=$posts['phone_id']?>" class="link-success">Xem chi tiết</a></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<h3 class="text-center">Bình luận</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <td class="text-center align-middle">Mã đánh giá</td>
            <td class="text-center align-middle">Tên</td>
            <td class="text-center align-middle">Số điện thoại</td>
            <td class="text-center align-middle">Email</td>
            <td class="text-center align-middle" width="200px">Nội dung</td>
            <td class="text-center align-middle">Thời gian</td>
            <td class="text-center align-middle">Trạng thái</td>
            <td class="text-center align-middle"></td>
            <td class="text-center align-middle"></td>
        </tr>
    </thead>
    <tbody>
        <?php
        while($comments = $params['comments']->fetch_assoc()) {
        ?>
            <tr>
                <td class="text-center align-middle"><?=$comments['id']?></td>
                <td class="text-center align-middle"><?=$comments['name']?></td>
                <td class="text-center align-middle"><?=$comments['phone']?></td>
                <td class="text-center align-middle"><?=$comments['email']?></td>
                <td class="text-center align-middle"><?=$comments['content']?></td>
                <td class="text-center align-middle"><?=time_elapsed_string($comments['time'])?></td>
                <?php
                if ($comments['is_censored']) {
                ?>
                    <td class="text-center align-middle text-success">Đã được kiểm duyệt</td>
                    <td class="text-center align-middle"><a role="button" onclick="destroyComment(<?=$comments['id']?>)" class="link-dark"><i class="bi bi-trash"></i></a></td>
                <?php
                } else {
                ?>
                    <td class="text-center align-middle text-danger">Đang chờ kiểm duyệt</td>
                    <td class="text-center align-middle">
                        <a role="button" onclick="censoreComment(<?=$comments['id']?>)" class="link-dark"><i class="bi bi-check-lg"></i></a>
                        <a role="button" onclick="destroyComment(<?=$comments['id']?>)" class="link-dark"><i class="bi bi-trash"></i></a>
                    </td>
                <?php
                }
                ?>
                <td class="text-center align-middle"><a href="index.php?action=phone&method=show&id=<?=$comments['phone_id']?>" class="link-success">Xem chi tiết</a></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<script>
    //Censore a post
    function censorePost(id) {
        $.get('index.php?action=post&method=censore&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=post&method=index';
            }
        });
    }

    //Destroy a post
    function destroyPost(id) {
        $.get('index.php?action=post&method=destroy&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=post&method=index';
            }
        });
    }
    //Censore a comment
    function censoreComment(id) {
        $.get('index.php?action=comment&method=censore&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=post&method=index';
            }
        });
    }

    //Destroy a comment
    function destroyComment(id) {
        $.get('index.php?action=comment&method=destroy&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=post&method=index';
            }
        });
    }
</script>
<?php
include __DIR__ . '/layouts/footer.php';
?>