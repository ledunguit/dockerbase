<div class="container-fluid">
    <div class="main-content border-none pt-3">
        <h2 class="text-center">Quản lý thông báo</h2>
        <?php if (isset($this->success)) : ?>
        <p class="text-center alert alert-success"><?=$this->success?></p>
        <?php elseif (isset($this->failed)) : ?>
            <p class="text-center alert alert-danger"><?=$this->failed?></p>
        <?php endif; ?>
        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus mr-2"></i>Tạo thông báo</button>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Tiêu đề</th>
                    <th>Mô tả</th>
                    <th>Thời gian</th>
                    <th>Người đăng</th>
                    <th>Trạng thái</th>
                    <th>Chức năng</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($this->list as $key) {
                    $i++;
                    if ($key['visible'] === 'Hiện') {
                        $control ='<a href="javascript:hide('.$key['id'].')"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalHide"><i class="fas fa-eye-slash mr-2"></i>Ẩn</button></a>';
                    } else {
                        $control = '<a href="javascript:show('.$key['id'].')"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalShow"><i class="fas fa-eye mr-2"></i>Hiện</button></a>';
                    }
                    echo '<tr>
                <td>'.$i.'</td>
                <td class="font-weight-bold">'.$key['title'].'</td>
                <td>'.$key['description'].'</td>
                <td>'.$key['timecreated'].'</td>
                <td>'.$key['author'].'</td>
                <td>'.$key['visible'].'</td>
                <td style="width: 280px;">
                    <a href="javascript:view('.$key['id'].')"><button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalEdit"><i class="fas fa-pencil-alt mr-2"></i>Sửa</button></a>
                    '.$control.'
                    <a href="javascript:deleteItem('.$key['id'].')"><button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modalDelete"><i class="fas fa-trash-alt mr-2"></i>Xóa</button></a>
                </td>
            </tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="modalEdit">
    <div class="modal-dialog modal-lg">
        <form method="post" id="form-edit-notify">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Chỉnh sửa thông báo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="noti-title-edit">Tiêu đề:</label>
                        <input type="text" class="form-control" id="noti-title-edit" name="NotiEdit[title]">
                    </div>
                    <div class="form-group">
                        <label for="edit-description-edit">Mô tả (tối đa 255 ký tự):</label>
                        <textarea class="form-control" rows="3" id="noti-description-edit" name="NotiEdit[description]"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="noti-content-edit">Nội dung thông báo:</label>
                        <textarea class="form-control" rows="5" id="noti-content-edit" name="NotiEdit[content]"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="noti-id" name="NotiEdit[id]">
                    <button type="submit" id="btn-update-noti" class="btn btn-primary">Xong</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="modalAdd">
    <div class="modal-dialog modal-lg">
        <form method="post" id="form-add-notify">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tạo mới thông báo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add-title">Tiêu đề:</label>
                        <input type="text" class="form-control" id="noti-title-add" name="NotiAdd[title]">
                    </div>
                    <div class="form-group">
                        <label for="add-description">Mô tả (tối đa 255 ký tự):</label>
                        <textarea class="form-control" rows="3" id="noti-description-add" name="NotiAdd[description]"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="add-content">Nội dung thông báo:</label>
                        <textarea class="form-control" rows="5" id="noti-content-add" name="NotiAdd[content]"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-add-noti" class="btn btn-success">Xong</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="modalHide">
    <div class="modal-dialog">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ẩn thông báo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn <b id="status">ẩn</b> thông báo này?
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="noti-hide-id" name="noti-hide-id">
                    <button type="submit" class="btn btn-warning">Ẩn</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="modalShow">
    <div class="modal-dialog">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hiện thông báo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn <b id="status">hiện</b> thông báo này?
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="noti-show-id" name="noti-show-id">
                    <button type="submit" class="btn btn-warning">Hiện</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="modalDelete">
    <div class="modal-dialog">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xóa thông báo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn <b>xóa</b> thông báo này?
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="noti-delete-id" name="noti-delete-id">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function hide(id) {
        $("#noti-hide-id").val(id);
    }

    function show(id) {
        $("#noti-show-id").val(id);
    }

    function deleteItem(id) {
        $("#noti-delete-id").val(id);
    }

    CKEDITOR.replace('noti-content-edit', {
        extraPlugins: 'uploadimage',
    });
    CKEDITOR.replace('noti-content-add', {
        extraPlugins: 'uploadimage',
    });
    var list = Object.assign({}, <?php echo json_encode($this->list)?>);
    function view(id){
        var notification = list[id - 1];
        $("#noti-id").val(notification.id);
        $('#noti-title-edit').val(notification.title);
        $('#noti-description-edit').val(notification.description);
        CKEDITOR.instances['noti-content-edit'].setData(rhtmlspecialchars(notification.content));
    }
    function rhtmlspecialchars(str) {
        if (typeof(str) == "string") {
            str = str.replace(/&gt;/ig, ">");
            str = str.replace(/&lt;/ig, "<");
            str = str.replace(/&#039;/g, "'");
            str = str.replace(/&quot;/ig, '"');
            str = str.replace(/&amp;/ig, '&');
        }
        return str;
    }

    $("#btn-add-noti").click(function(e) {
        e.preventDefault();
        var isValid = true;
        if ($("#noti-title-add").val().length == 0 || $("#noti-title-add").val() == null) {
            isValid = false;
            $("#noti-title-add").attr('placeholder', 'Vui lòng nhập tiêu đề thông báo!');
            $("#noti-title-add").addClass('plc-red');
        }
        if ($("#noti-description-add").val().length == 0 || $("#noti-description-add").val() == null) {
            isValid = false;
            $("#noti-description-add").attr('placeholder', 'Vui lòng nhập mô tả thông báo!');
            $("#noti-description-add").addClass('plc-red');
        }
        if (CKEDITOR.instances['noti-content-add'].getData() == '' || CKEDITOR.instances['noti-content-add'].getData() == null) {
            isValid = false;
            alert("Vui lòng nhập nội dung thông báo!");
        }
        if (isValid) {
            $("#form-add-notify").submit();
        }
    })

    $("#noti-title-add").keydown(function() {
        $("#noti-title-add").removeClass('plc-red');
        $("#noti-title-add").attr('placeholder', 'Tiêu đề');
    })
    $("#noti-description-add").keydown(function() {
        $("#noti-description-add").removeClass('plc-red');
        $("#noti-description-add").attr('placeholder', 'Mô tả');
    })

    $("#btn-update-noti").click(function(e) {
        e.preventDefault();
        var isValid = true;
        if ($("#noti-title-edit").val().length == 0 || $("#noti-title-edit").val() == null) {
            isValid = false;
            $("#noti-title-edit").attr('placeholder', 'Vui lòng nhập tiêu đề thông báo!');
            $("#noti-title-edit").addClass('plc-red');
        }
        if ($("#noti-description-edit").val().length == 0 || $("#noti-description-edit").val() == null) {
            isValid = false;
            $("#noti-description-edit").attr('placeholder', 'Vui lòng nhập mô tả thông báo!');
            $("#noti-description-edit").addClass('plc-red');
        }
        if (CKEDITOR.instances['noti-content-edit'].getData() == '' || CKEDITOR.instances['noti-content-edit'].getData() == null) {
            isValid = false;
            alert("Vui lòng nhập nội dung thông báo!");
        }
        if (isValid) {
            $("#form-edit-notify").submit();
        }
    })

    $("#noti-title-edit").keydown(function() {
        $("#noti-title-edit").removeClass('plc-red');
        $("#noti-title-edit").attr('placeholder', 'Tiêu đề');
    })
    $("#noti-description-edit").keydown(function() {
        $("#noti-description-edit").removeClass('plc-red');
        $("#noti-description-edit").attr('placeholder', 'Mô tả');
    })
</script>