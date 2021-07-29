<?php
include __DIR__ . '/layouts/header.php';
?>
<h3 class="text-center">Quản lý điện thoại</h3>
<button class="btn btn-danger mb-3" onclick="openAddForm()">Thêm điện thoại</button>
<table class="table table-bordered">
    <thead>
        <tr>
            <td>Mã điện thoại</td>
            <td>Tên điện thoại</td>
            <td>Hình</td>
            <td>Nhà sản xuất</td>
            <td>Màu sắc</td>
            <td>Số lượng</td>
            <td>Giá</td>
            <td>Giảm giá (%)</td>
            <td>Mở kinh doanh</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php
        while($phone = $params['data']->fetch_assoc()) {
        ?>
            <tr>
                <td class="text-center align-middle"><?=$phone['id']?></td>
                <td class="text-center align-middle"><?=$phone['name']?></td>
                <td class="text-center align-middle"><img src="public/images/phones/phone<?=$phone['id']?>.png" height="50px" /></td>
                <td class="text-center align-middle"><?=$phone['brand_name']?></td>
                <td class="text-center align-middle" style="background-color: #<?=$phone['color']?>;"></td>
                <td class="text-center align-middle"><?=$phone['quantity']?></td>
                <td class="text-center align-middle"><?=number_format($phone['price'])?>đ</td>
                <td class="text-center align-middle"><?=$phone['discount']?>%</td>
                <?php
                if ($phone['is_opened']) {
                ?>
                    <td class="text-center align-middle text-success">Còn mở</td>
                <?php
                } else {
                ?>
                    <td class="text-center align-middle text-danger">Đã đóng</td>
                <?php
                }
                ?>
                <td class="text-center align-middle">
                    <a class="d-inline link-secondary" href="index.php?action=phone&method=show&id=<?=$phone['id']?>"><i class="bi bi-menu-button-wide"></i></a>
                    <div class="d-inline" role="button" onclick="openUpdateForm(<?=$phone['id']?>, '<?=$phone['name']?>', <?=$phone['brand_id']?>, '<?=$phone['color']?>', <?=$phone['quantity']?>, <?=$phone['price']?>, <?=$phone['discount']?>, <?=$phone['is_opened']?>)"><i class="bi bi-pen"></i></div>
                    <div class="d-inline" role="button" onclick="openDestroyForm(<?=$phone['id']?>)"><i class="bi bi-trash"></i></div>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<!--Add phone modal-->
<div class="modal fade" id="add">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Thêm điện thoại mới</div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-2">
                    <span class="input-group-text">Tên điện thoại</span>
                    <input id="add-phone-name" type="text" class="form-control" placeholder="Nhập tên điện thoại" />
                </div>
                <div class="input-group mb-2">
                    <label class="input-group-text" for="inputGroupSelect01">Hãng điện thoại</label>
                    <select class="form-select" id="add-phone-brand">
                        <?php
                        while ($brand = $params['brands']->fetch_assoc()) {
                        ?>
                            <option value="<?=$brand['id']?>"><?=$brand['name']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Màu sắc</span>
                    <input id="add-phone-color" type="color" class="form-control form-control-color" id="exampleColorInput" value="#563d7c" title="Chọn màu sắc">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Số lượng</span>
                    <input id="add-phone-quantity" type="number" class="form-control" placeholder="Nhập số lượng" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Giá</span>
                    <input id="add-phone-price" type="number" class="form-control" placeholder="Nhập giá" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Giảm giá</span>
                    <input id="add-phone-discount" type="number" class="form-control" placeholder="Nhập giảm giá (%)" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Ảnh</span>
                    <input id="add-phone-image" type="file" accept="image/png, image/jpeg" class="form-control" placeholder="Chọn ảnh..." />
                </div>
                <div class="form-check">
                    <input id="add-phone-is-opened" class="form-check-input" type="checkbox" checked>
                    <label class="form-check-label" for="flexCheckDefault">Mở kinh doanh</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button id="add-phone" type="button" class="btn btn-primary" onclick="addPhone()">Thêm điện thoại mới</button>
            </div>
        </div>
    </div>
</div>
<!--End of add new phone-->
<!--Update the glass modal-->
<div class="modal fade" id="update">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Chỉnh sửa điện thoại</div>
            </div>
            <div class="modal-body">
            <div class="input-group mb-2">
                    <span class="input-group-text">Tên điện thoại</span>
                    <input id="update-phone-name" type="text" class="form-control" placeholder="Nhập tên điện thoại" />
                </div>
                <div class="input-group mb-2">
                    <label class="input-group-text" for="inputGroupSelect01">Hãng điện thoại</label>
                    <select class="form-select" id="update-phone-brand">
                        <?php
                        $params['brands']->data_seek(0);
                        while ($brand = $params['brands']->fetch_assoc()) {
                        ?>
                            <option value="<?=$brand['id']?>"><?=$brand['name']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Màu sắc</span>
                    <input id="update-phone-color" type="color" class="form-control form-control-color" id="exampleColorInput" value="#563d7c" title="Chọn màu sắc">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Số lượng</span>
                    <input id="update-phone-quantity" type="number" class="form-control" placeholder="Nhập số lượng" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Giá</span>
                    <input id="update-phone-price" type="number" class="form-control" placeholder="Nhập giá" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Giảm giá</span>
                    <input id="update-phone-discount" type="number" class="form-control" placeholder="Nhập giảm giá (%)" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Ảnh</span>
                    <input id="update-phone-image" type="file" accept="image/png, image/jpeg" class="form-control" placeholder="Chọn ảnh..." />
                </div>
                <div class="form-check">
                    <input id="update-phone-is-opened" class="form-check-input" type="checkbox" checked>
                    <label class="form-check-label" for="flexCheckDefault">Mở kinh doanh</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button id="update-glass" type="button" class="btn btn-primary" onclick="updatePhone()">Cập nhật điện thoại</button>
            </div>
        </div>
    </div>
</div>
<!--End of update the glass-->
<!--Destroy a glass modal-->
<div class="modal fade" id="destroy">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Bạn có muốn xóa?</div>
            </div>
            <div class="modal-body">
                Sau khi xóa sẽ không thể khôi phục lại dữ liệu!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-danger" onclick="destroyPhone()">Xác nhận xóa</button>
            </div>
        </div>
    </div>
</div>
<!--End of Destroy a glass modal-->
<script>
    let selectedId = 0;

    function openAddForm() {
        $('#add-phone-name').val('');
        $('#add-phone-brand').val('');
        $('#add-phone-color').val('');
        $('#add-phone-quantity').val('');
        $('#add-phone-price').val('');
        $('#add-phone-discount').val('');
        $('#add-phone-image').val('');
        $('#add-phone-is-opened').prop('checked', true);
        $('#add').modal('show');
    }

    function openUpdateForm(id, name, brand_id, color, quantity, price, discount, isOpened) {
        selectedId = id;
        $('#update-phone-name').val(name);
        $('#update-phone-brand').prop('value', brand_id);
        $('#update-phone-color').val('#' + color);
        $('#update-phone-quantity').val(quantity);
        $('#update-phone-price').val(price);
        $('#update-phone-discount').val(discount);
        $('#update-phone-image').val('');
        $('#update-phone-is-opened').prop('checked', isOpened);
        $('#update').modal('show');
    }

    function openDestroyForm(id) {
        selectedId = id;
        $('#destroy').modal('show');
    }

    function addPhone() {
        name = $('#add-phone-name').val();
        brand = $('#add-phone-brand').children("option:selected").val();
        color = $('#add-phone-color').val();
        quantity = $('#add-phone-quantity').val();
        price = $('#add-phone-price').val();
        discount = $('#add-phone-discount').val();
        image = $('#add-phone-image').prop('files')[0];
        isOpened = $('#add-phone-is-opened').prop('checked') ? 1 : 0;

        data = new FormData();
        data.append('name', name);
        data.append('brand', brand);
        data.append('color', color);
        data.append('quantity', quantity);
        data.append('price', price);
        data.append('discount', discount);
        data.append('image', image);
        data.append('is_opened', isOpened);

        $.ajax({
            url: 'index.php?action=phone&method=store',
            dataType: 'script',
            cache: false,
            contentType: false,
            processData: false,
            data: data,                         
            type: 'post',
            success: function(data) {
                if (data) {
                    window.location.href = 'index.php?action=phone&method=index';
                }
            }
        });
    }

    //Update a phone
    function updatePhone() {
        id = selectedId;
        name = $('#update-phone-name').val();
        brand = $('#update-phone-brand').children("option:selected").val();
        color = $('#update-phone-color').val();
        quantity = $('#update-phone-quantity').val();
        price = $('#update-phone-price').val();
        discount = $('#update-phone-discount').val();
        image = $('#update-phone-image').prop('files')[0];
        isOpened = $('#update-phone-is-opened').prop('checked') ? 1 : 0;

        data = new FormData();
        data.append('id', id);
        data.append('name', name);
        data.append('brand', brand);
        data.append('color', color);
        data.append('quantity', quantity);
        data.append('price', price);
        data.append('discount', discount);
        data.append('image', image);
        data.append('is_opened', isOpened);

        $.ajax({
            url: 'index.php?action=phone&method=update',
            dataType: 'script',
            cache: false,
            contentType: false,
            processData: false,
            data: data,                         
            type: 'post',
            success: function(data) {
                if (data) {
                    window.location.href = 'index.php?action=phone&method=index';
                }
            }
        });
    }

    function destroyPhone() {
        let id = selectedId;
        $.get('index.php?action=phone&method=destroy&id=' + id, function (data) {
            if (data) {
                window.location.href = 'index.php?action=phone&method=index';
            }
        });
    }
</script>
<?php
include __DIR__ . '/layouts/footer.php';
?>