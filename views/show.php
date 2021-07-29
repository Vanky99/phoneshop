<?php
include __DIR__ . '/layouts/header.php';
include_once __DIR__ . '/../functions.php';
?>
<style>
    .glass-image {
        height: 300px;
        background-repeat:no-repeat;
        background-size: contain;
        background-position: center;
    }
    .glass-color {
        border-radius: 15%;
        width: 50px;
        height: 50px;
    }
    .post-avatar {
        border-radius: 10%;
        width: 75px;
        height: 75px;
    }
    .comment-avatar {
        border-radius: 10%;
        width: 50px;
        height: 50px;
    }
    .hover-image {
        transition: transform .2s;
    }

    .hover-image:hover {
        transform: scale(1.1);
    }
    .star-color {
        color: #ffe921;
    }
</style>
<!--Title-->
<div class="row mb-4 mt-2">
    <div class="col">
        <h1><?=$params['name']?></h1>
        <hr />
        <!--Rating-->
        <div>Đánh giá:
            <?php
            if ($params['rating'] > 0) {
                for ($index = 1; $index <= 5; $index++) {
            ?>
                    <i class="star-color bi <?=$index <= $params['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
            <?php
                }
            }
            else {
            ?>
                Chưa có đánh giá
            <?php
            }
            ?>
        </div>
        <!--End of rating-->
    </div>
</div>
<!--End of title-->
<!--Glass-->
<div class="row p-4 mb-5">
    <!--Image-->
    <div class="col-md-6">
        <div id="image" style="background-image: url('<?='public/images/phones/phone' . $params['id'] . '.png'?>')" class="w-100 glass-image hover-image"></div>
    </div>
    <!--End of image-->
    <!--Information-->
    <div class="col-md-6 pt-5">
        <?php
        if ($params['is_opened'] || $_IS_ADMIN) {
        ?>
            <?php
            if (!$params['is_opened'] && $_IS_ADMIN) {
            ?>
                <h4 class="text-muted">Ngừng kinh doanh</h4>
            <?php
            }
            ?>
            <div class="mb-2">
                <h3 class="d-sm-inline text-danger">Giá: <?=number_format($params['price'] - ($params['price'] * $params['discount'] / 100))?>đ</h3>
                <h5 class="d-sm-inline text-secondary text-decoration-line-through"><?=number_format($params['price'])?>đ</h5 >
                <h4 class="d-sm-inline text-danger"> -<?=$params['discount']?>%</h4>
            </div>
        <?php
        } else {
        ?>
            <h2 class="text-danger">Sản phẩm ngừng kinh doanh</h2>
        <?php
        }
        ?>
        <div class="mb-3">Nhà sản xuất: <?=$params['brand_name']?></div>
        <div class="mb-3">
            Màu:
            <div class="glass-color" style="background-color: #<?=$params['color']?>;"></div>
        </div>
        <div class="mb-2">Số lượng kho: <?=$params['quantity']?></div>
        <div class="d-flex justify-content-start">
            <?php
            if ($params['is_opened'] == 0 || $_IS_ADMIN) {
            ?>

            <?php
            } else if ($params['quantity'] > 0) {
            ?>
                <button id="buy" class="btn btn-danger px-5 py-2"><h4>Đặt hàng ngay</h4></button>
            <?php
            } else {
            ?>
                <a class="btn btn-secondary px-5 disabled" href=""><h5>Tạm thời hết hàng</h5></a>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!--End of glass-->
<!--End of information-->
<hr />
<!--Posts-->
<div class="row p-4">
    <!--Title-->
    <h2>Đánh giá</h2>
    <!--End of title-->
    <!--Rating-->
    <div class="row">
        <!--Average rating-->
        <div class="col-12">
            <div class="d-flex flex-column align-items-center">
                <h5>Đánh giá trung bình</h5>
                <h2><?=number_format($params['rating'], 1)?>/5</h2>
                <div>
                    <?php
                    if ($params['posts_count'] > 0) {
                        for ($index = 1; $index <= 5; $index++) {
                    ?>
                            <i class="star-color bi h4 <?=$index <= $params['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                    <?php
                        }
                    }
                    if ($params['posts_count'] > 0) {
                    ?>
                        <div class="text-center"><?=$params['posts_count']?> lượt đánh giá</div>
                    <?php
                    } else {
                    ?>
                        Chưa có đánh giá
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <!--End of Average rating-->
    </div>
    <!--End of rating-->
    <hr />
    <!--Add Post-->
    <div class="mb-3">
        <h3 class="mb-3">Bài viết đánh giá </h3>
        <button class="btn btn-danger" onclick="openPostModal()">Viết đánh giá</button>
    </div>
    <!--End of add post-->
    <!--Posts-->
    <?php
    if (count($params['posts']) > 0) {
        foreach ($params['posts'] as $post_data) {
            $post = $post_data['post'];
    ?>
            <div class="mb-2 round">
                <!--Post content-->
                <div class="d-flex align-items-center">
                    <div class="d-flex post-avatar justify-content-center align-items-center bg-secondary text-white m-3">
                        <h4><?=substr($post['name'], 0, 1)?></h4>
                    </div>
                    <div>
                        <h5>
                            <?=$post['name']?>
                            <?php
                            if ($_IS_ADMIN) {
                            ?>
                                <div class=" d-inline dropdown">
                                    <button class="btn" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                    <ul class="dropdown-menu">
                                        <?php
                                        if (!$post['is_censored']) {
                                        ?>
                                            <li><button onclick="censorePost(<?=$post['id']?>)" class="dropdown-item">Kiểm duyệt</button></li>
                                        <?php
                                        }
                                        ?>
                                        <li><button onclick="destroyPost(<?=$post['id']?>)" class="dropdown-item">Xóa</button></li>
                                    </ul>
                                </div>
                            <?php
                            }
                            ?>
                        </h5>
                        <?php
                        if ($_IS_ADMIN) {
                            if ($post['is_censored']) {
                        ?>
                                <div class="text-success">Đã được kiểm duyệt</div>
                        <?php
                            } else {
                        ?>
                            <div class="text-warning">Chưa được kiểm duyệt</div>
                        <?php
                            }
                        }
                        ?>
                        <div>
                            <?php
                            for ($index = 1; $index <= 5; $index++) {
                            ?>
                                <i class="star-color bi <?=$index <= $post['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                            <?php
                            }
                            ?>
                            <div class="d-inline fst-italic fw-light"><?= time_elapsed_string($post['time'])?></div>
                        </div>
                        <div><?=$post['content']?></div>
                    </div>
                </div>
                <!--End of post content-->
                <h5 class="mx-5">Bình luận</h5>
                <!--Post comment-->
                <?php
                $comments = $post_data['comments'];
                while($comment = $comments->fetch_assoc()) {
                ?>
                    <div class="d-flex align-items-center mx-5 bg-light mb-2 py-2">
                        <div class="d-flex align-items-center">
                            <div class="d-flex comment-avatar justify-content-center align-items-center bg-secondary text-white m-3">
                                <h4><?=isset($comment['user_id']) ? substr($comment['admin_first_name'], 0, 1) : substr($comment['name'], 0, 1)?></h4>
                            </div>
                        </div>
                        <div>
                            <h5>
                                <?php
                                if (isset($comment['user_id'])) {
                                ?>
                                <div class="text-danger"><?=$comment['admin_first_name'] . ' ' . $comment['admin_last_name'] . ' (Quản trị viên)'?></div>
                                <?php
                                } else {
                                    echo $comment['name'];
                                }
                                ?>
                                <?php
                                if ($_IS_ADMIN) {
                                ?>
                                    <div class=" d-inline dropdown">
                                        <button class="btn" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                        <ul class="dropdown-menu">
                                            <?php
                                            if (!$comment['is_censored']) {
                                            ?>
                                                <li><button onclick="censoreComment(<?=$comment['id']?>)" class="dropdown-item">Kiểm duyệt</button></li>
                                            <?php
                                            }
                                            ?>
                                            <li><button onclick="destroyComment(<?=$comment['id']?>)" class="dropdown-item">Xóa</button></li>
                                        </ul>
                                    </div>
                                <?php
                                }
                                ?>
                            </h5>
                            <?php
                            if ($_IS_ADMIN) {
                                if ($comment['is_censored']) {
                            ?>
                                    <div class="text-success">Đã được kiểm duyệt</div>
                            <?php
                                } else {
                            ?>
                                <div class="text-warning">Chưa được kiểm duyệt</div>
                            <?php
                                }
                            }
                            ?>
                            <div class="fst-italic fw-light"><?= time_elapsed_string($comment['time'])?></div>
                            <div><?=$comment['content']?></div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <button class="btn link-primary reply-post mx-5" value="<?=$post['id']?>"><h6>Thêm bình luận</h6></button>
                <!--End of post comment-->
            </div>
            <hr />
    <?php
        }
    } else {
    ?>
        <h4 class="text-center">Chưa có bài đánh giá</h4>
    <?php
    }
    ?>
    <!--End of posts-->
</div>
<!--End of posts-->
<!--Post Modal-->
<div class="modal fade" id="post">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Đánh giá</div>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <h5 class="mb-2">Hãy cho biết cảm nhận của bạn về sản phẩm của chúng tôi</h5>
                    <div class="d-flex justify-content-center">
                        <i id="star-1" onclick="setStars(1)" class="star-color bi bi-star h3 mx-2" data-toggle="tooltip" title="Rất tệ"></i>
                        <i id="star-2" onclick="setStars(2)" class="star-color bi bi-star h3 mx-2" data-toggle="tooltip" title="Tệ"></i>
                        <i id="star-3" onclick="setStars(3)" class="star-color bi bi-star h3 mx-2" data-toggle="tooltip" title="Bình thường"></i>
                        <i id="star-4" onclick="setStars(4)" class="star-color bi bi-star h3 mx-2" data-toggle="tooltip" title="Hài lòng"></i>
                        <i id="star-5" onclick="setStars(5)" class="star-color bi bi-star h3 mx-2" data-toggle="tooltip" title="Rất hài lòng"></i>
                    </div>
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Nội dung</span>
                    <input id="send-post-content" type="text" class="form-control" placeholder="Nội dung" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Họ tên</span>
                    <input id="send-post-name" type="text" class="form-control" placeholder="Họ tên" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Số điện thoại</span>
                    <input id="send-post-phone" type="text" class="form-control" placeholder="Số điện thoại (Tùy chọn)" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Email</span>
                    <input id="send-post-content" type="text" class="form-control" placeholder="Email (Tùy chọn)" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button id="send-post" type="button" class="btn btn-danger">Gửi đánh giá</button>
            </div>
        </div>
    </div>
</div>
<!--End of post modal-->
<!--Comment-->
<div class="modal fade" id="comment">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Bình luận</div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-2">
                    <span class="input-group-text">Nội dung</span>
                    <input id="send-comment-content" type="text" class="form-control" placeholder="Nội dung" />
                </div>
                <?php
                if (!$_IS_ADMIN) {
                ?>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Họ tên</span>
                        <input id="send-comment-name" type="text" class="form-control" placeholder="Họ tên" />
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Số điện thoại</span>
                        <input id="send-comment-phone" type="text" class="form-control" placeholder="Số điện thoại(Tùy chọn)" />
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Email</span>
                        <input id="send-comment-content" type="text" class="form-control" placeholder="Email(Tùy chọn)" />
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button id="send-comment" type="button" class="btn btn-danger">Gửi bình luận</button>
            </div>
        </div>
    </div>
</div>
<!--End of comment-->
<!--Message modal-->
<div class="modal fade" id="message">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Thông báo</div>
            </div>
            <div class="modal-body" id="message-body">
                Cảm ơn bạn đã gửi đánh giá sản phẩm cho chúng tôi, đội kiểm duyệt sẽ kiểm duyệt đánh giá của bạn sớm nhất có thể
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!--End of message modal-->

<script>
    let currentStar = 0;
    let currentPostId = 0;

    //Set star of post
    function setStars(star) {
        currentStar = star;
        for (index = 1; index <= 5; index++) {
            if (index <= star)
                $('#star-' + index).removeClass('bi-star').addClass('bi-star-fill');
            else
                $('#star-' + index).removeClass('bi-star-fill').addClass('bi-star');
        }
    }

    function openPostModal() {
        setStars(0);
        $('#send-post-content').val('');
        $('#send-post-name').val('');
        $('#send-post-phone').val('');
        $('#send-post-email').val('');
        $('#post').modal('show');
    }
    
    //Censore a post
    function censorePost(id) {
        $.get('index.php?action=post&method=censore&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=glass&method=show&id=<?=$params['id']?>';
            }
        });
    }

    //Destroy a post
    function destroyPost(id) {
        $.get('index.php?action=post&method=destroy&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=glass&method=show&id=<?=$params['id']?>';
            }
        });
    }

    //Censore a comment
    function censoreComment(id) {
        $.get('index.php?action=comment&method=censore&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=glass&method=show&id=<?=$params['id']?>';
            }
        });
    }

    //Destroy a comment
    function destroyComment(id) {
        $.get('index.php?action=comment&method=destroy&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=glass&method=show&id=<?=$params['id']?>';
            }
        });
    }

    //Buy button clicked
    $('#buy').click(function() {
        $.get('index.php?action=cart&method=buy&id=<?=$params['id']?>&quantity=1').done(function(data) {
            if (data) {
                window.location.href = 'index.php?action=cart&method=index';
            }
        });
    });

    //Send a post
    $('#send-post').click(function() {
        star = currentStar;
        content = $('#send-post-content').val();
        name = $('#send-post-name').val();
        phone = $('#send-post-phone').val();
        email = $('#send-post-email').val();

        if ((star < 1 || star > 5)) {
            $('#message-body').text('Bạn chưa đánh giá sao');
            $('#message').modal('show');
            return;
        }

        if (content == '') {
            $('#message-body').text('Bạn chưa nhập nội dung');
            $('#message').modal('show');
            return;
        }

        if (name == '') {
            $('#message-body').text('Bạn chưa nhập họ tên');
            $('#message').modal('show');
            return;
        }

        data = {
            rating: star,
            content: content,
            name: name,
            phone: phone,
            email: email,
        }

        $.post('index.php?action=phone&method=add_post&id=<?=$params['id']?>', data).done(function(data) {
            if (data) {
                $('#post').modal('hide');
                $('#message-body').text('Cảm ơn bạn đã đánh giá sản phẩm. Chúng tôi đã nhận được bài đánh giá và sẽ phản hồi sớm nhất');
                $('#message').modal('show');
            }
        });
    });

    //Reply a post clicked
    $('.reply-post').each(function (index, elem) {
        $(elem).click(function () {
            currentPostId = $(this).attr('value');
            $('#send-comment-content').val('');
            $('#send-comment-name').val('');
            $('#send-comment-phone').val('');
            $('#send-comment-email').val('');
            $('#comment').modal('show');
        });
    });

    //Send a comment
    $('#send-comment').click(function() {
        postId = currentPostId;
        content = $('#send-comment-content').val();
        name = $('#send-comment-name').val();
        phone = $('#send-comment-phone').val();
        email = $('#send-comment-email').val();

        if (content == '') {
            $('#message-body').text('Bạn chưa nhập nội dung');
            $('#message').modal('show');
            return;
        }

        <?php
        if (!$_IS_ADMIN) {
        ?>
            if (name == '') {
                $('#message-body').text('Bạn chưa nhập họ tên');
                $('#message').modal('show');
                return;
            }
        <?php
        }
        ?>

        data = {
            content: content,
            name: name,
            phone: phone,
            email: email,
        }

        $.post('index.php?action=post&method=add_comment&id=' + postId, data).done(function(data) {
            if (data) {
                <?php
                if (!$_IS_ADMIN) {
                ?>
                    $('#comment').modal('hide');
                    $('#message-body').text('Chúng tôi đã nhận được bài bình luận của bạn và sẽ phản hồi sớm nhất');
                    $('#message').modal('show');
                <?php
                } else {
                ?>
                    window.location.href = 'index.php?action=phone&method=show&id=<?=$params['id']?>';
                <?php
                }
                ?>
            }
        });
    });
</script>
<?php
include __DIR__ . '/layouts/footer.php';
?>