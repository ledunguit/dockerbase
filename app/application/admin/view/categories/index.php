<div class="container-fluid"></br>
    <?php if (isset($this->success)): ?>
        <div class="alert alert-success"><?=$this->success?></div>
    <?php elseif (isset($this->error)): ?>
        <div class="alert alert-danger"><?=$this->error?></div>
    <?php endif; ?>
    <div class="main-content p-3">
        <h2>Quản lý danh mục</h2>
        <p>Danh sách tất cả các danh mục.</p>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i
                    class="fas fa-plus mr-2"></i>Tạo danh mục
        </button>
        <div class="row mt-3">
            <div class="col-sm-5">
                <ul class="list-group list-group-flush">
                    <?php
                    $i = 0;
                    $str = '';
                    foreach ($this->list as $key) {
                        $i++;
                        if($key['id'] == 1){
                            continue;
                        }
                        $path = \Venus\Venus::$adminUrl . '/publics/images/categories/' . $key['shortname'] . '.png';
                        if (getimagesize($path)) {
                        } else {
                            $path = \Venus\Venus::$adminUrl . '/publics/images/categories/default.png';
                        }
                        $class = 'even';
                        if ($i % 2 == 0)
                            $class = 'odd';
                        $str .= '<li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-2">
                        <div class="left"><img src="' . $path . '"/></div>
                        <div class="right">
                            <a href="javascript:viewItem('.$i.')" class="h7 text-primary">' . $key['name'] . '</a>
                        </div>
                        </li>';
                    }
                    if ($str == '')
                        echo '<div class="alert alert-warning">Chưa có danh mục nào !</div>';
                    else
                        echo $str;
                    ?>
                </ul>
            </div>
            <div class="col-sm-7">
                <div class="d-flex">
                    <a href="#" id="href-edit" class="ml-auto mr-3"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEdit" id="btn-edit" style="display: none">
                            <i class="fas fa-pencil-alt mr-2"></i>Chỉnh sửa</button></a>
                    <a href="#" id="href-delete"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDelete" id="btn-delete" style="display: none">
                            <i class="fas fa-trash-alt mr-2"></i>Xóa</button></a>
                </div>
                <div class="alert alert-danger mt-3 mb-2" id="visible-result" style="display: none"></div>
                <table class="table table-bordered mt-3">
                    <tr>
                        <td style="width:25%" class="font-weight-bold">Tên danh mục:</td>
                        <td id="name"></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Mô tả</td>
                        <td id="description"></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Số đề thi:</td>
                        <td id="number-quiz"></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Số lượt làm:</td>
                        <td id="number-attempts"></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Số câu hỏi:</td>
                        <td id="number-ques"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog modal-md">
        <form method="post" id="form-add-cate" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tạo danh mục mới</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cate-create-name">Tên danh mục:</label>
                        <input type="text" class="form-control" id="cate-create-name" name="CateCreate[name]">
                    </div>
                    <div class="form-group">
                        <label for="cate-create-shortname">Tên rút gọn (không quá 20 ký tự, không dấu câu, kí tự đặc biệt):</label>
                        <input type="text" class="form-control" id="cate-create-shortname" name="CateCreate[shortname]">
                    </div>
                    <div class="form-group">
                        <label for="cate-create-description">Mô tả:</label>
                        <textarea class="form-control" rows="5" id="cate-create-description" name="CateCreate[description]"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cate-image-add">Hình danh mục:</label>
                        <input type="file" name="cate-image-add" id="cate-image-add">
                    </div>
                    <div class="form-check">
                        <label for="cate-create-hide" class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="cate-create-hide" name="CateCreate[hide]">Ẩn danh mục này
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnCreateCate" class="btn btn-success mr-2">Tạo</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="modalEdit">
    <div class="modal-dialog modal-md">
        <form method="post" id="form-edit-cate" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Chỉnh sửa danh mục</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cate-edit-name">Tên danh mục:</label>
                        <input type="text" class="form-control" id="cate-edit-name" name="CateEdit[name]">
                    </div>
                    <div class="form-group">
                        <label for="cate-edit-shortname">Tên rút gọn (không quá 20 ký tự, không dấu câu, kí tự đặc biệt):</label>
                        <input type="text" class="form-control" id="cate-edit-shortname" name="CateEdit[shortname]">
                    </div>
                    <div class="form-group">
                        <label for="cate-edit-description">Mô tả:</label>
                        <textarea class="form-control" rows="5" id="cate-edit-description" name="CateEdit[description]"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cate-image-edit">Hình danh mục thay thế:</label>
                        <input type="file" name="cate-image-edit" id="cate-image-edit">
                    </div>
                    <div class="form-check">
                        <label for="cate-edit-hide" class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="cate-edit-hide"
                                name="CateEdit[hide]">Ẩn danh mục này
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="cateId" name="CateEdit[id]" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnUpdateCate" class="btn btn-success mr-2">Chỉnh sửa</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="modalDelete">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger"><i class="fas fa-trash-alt mr-2"></i>Xóa danh mục</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="confirm-delete"></p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger mr-2" data-dismiss="modal">Xóa</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Đóng</button>
            </div>

        </div>
    </div>
</div>
<script>
    list = Object.assign({}, <?php echo json_encode($this->list)?>);
    function viewItem(id) {
        category = list[id - 1];
        $('#name').text(category.name);
        $('#description').text(category.description);
        $('#number-quiz').text(category.numberOfQuizzes);
        $('#number-attempts').text(category.numberOfAttempts);
        $('#number-ques').text(category.numberOfQuestions);
        $('#btn-edit').css('display', 'block');
        $('#btn-delete').css('display', 'block');
        $('#href-edit').attr('href', 'javascript:editItem(' + id + ')');
        $('#href-delete').attr('href', 'javascript:deleteItem(' + id + ')');
        if(category.visible == 0) {
            $('#visible-result').css('display', 'block');
            $('#visible-result').text('Danh mục này đang bị ẩn.');
        }
        else {
            $('#visible-result').css('display', 'none');
        }
    }
    function editItem(id) {
        category = list[id - 1];
        $('#cate-edit-name').val(category.name);
        $('#cate-edit-shortname').val(category.shortname);
        $('#cate-edit-description').val(category.description);
        $('#cateId').val(category.id);
        if(category.visible == 0) {
            $('#cate-edit-hide').prop('checked', true);
        }
        else {
            $('#cate-edit-hide').prop('checked', false);
        }
    }
    function deleteItem(id) {
        category = list[id - 1];
        $('#confirm-delete').text('Bạn có chắc chắn muốn xóa danh mục "' + category.name + '" không?')
    }

    function removeAscent(str) {
        if (str === null || str === undefined) return str;
        str = str.replace(/\s+/g, '-');
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        return str;
    }

    $("#cate-create-name").keyup(function() {
        $("#cate-create-shortname").val(removeAscent($("#cate-create-name").val()));
    })

    $("#cate-edit-name").keyup(function() {
        $("#cate-edit-shortname").val(removeAscent($("#cate-edit-name").val()));
    })

    $("#btnCreateCate").click(function(e){
        e.preventDefault();
        var valid = true;
        const cate_create_name = $("#cate-create-name").val();
        const cate_create_shortname = $("#cate-create-shortname").val();
        if (cate_create_name == '' || cate_create_name == null) {
            valid = false;
            $("#cate-create-name").attr('placeholder', '*Vui lòng nhập tên danh mục.');
            $("#cate-create-name").addClass('plc-red');
        }

        if (cate_create_shortname == '' || cate_create_shortname == null) {
            valid = false;
            $("#cate-create-shortname").attr('placeholder', '*Vui lòng nhập tên rút gọn của danh mục.');
            $("#cate-create-shortname").addClass('plc-red');
        } else if (cate_create_shortname.length > 20) {
            valid = false;
            $("#cate-create-shortname").val('');
            $("#cate-create-shortname").focus(function() {
                $("#cate-create-shortname").val(cate_create_shortname);
            })
            $("#cate-create-shortname").attr('placeholder', '*Tên rút gọn của danh mục không được quá 20 kí tự.');
            $("#cate-create-shortname").addClass('plc-red');
        }
        if (valid) {
            $("#form-add-cate").submit();
        }
    })

    $("#btnUpdateCate").click(function(e) {
        e.preventDefault();
        var valid = true;
        const cate_edit_name = $("#cate-edit-name").val();
        const cate_edit_shortname = $("#cate-edit-shortname").val();
        if (cate_edit_name == '' || cate_edit_name == null) {
            valid = false;
            $("#cate-edit-name").attr('placeholder', '*Vui lòng nhập tên danh mục.');
            $("#cate-edit-name").addClass('plc-red');
        }

        if (cate_edit_shortname == '' || cate_edit_shortname == null) {
            valid = false;
            $("#cate-edit-shortname").attr('placeholder', '*Vui lòng nhập tên rút gọn của danh mục.');
            $("#cate-edit-shortname").addClass('plc-red');
        } else if (cate_edit_shortname.length > 20) {
            valid = false;
            $("#cate-edit-shortname").val('');
            $("#cate-edit-shortname").focus(function() {
                $("#cate-edit-shortname").val(cate_edit_shortname);
            })
            $("#cate-edit-shortname").attr('placeholder', '*Tên rút gọn của danh mục không được quá 20 kí tự.');
            $("#cate-edit-shortname").addClass('plc-red');
        }
        if (valid) {
            $("#form-edit-cate").submit();
        }
    })

    $("#cate-create-name").keyup(function() {
        $(this).attr('placeholder', '');
    })

    $("#cate-create-shortname").keyup(function() {
        $(this).attr('placeholder', '');
    })
</script>