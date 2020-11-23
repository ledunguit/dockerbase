<div class="container-fluid">
    <div id="alert-success" style="display: none;">
        <div class="alert alert-success alert-dismissible">
            <strong id="alert-success-title">Thành công!</strong><span id="alert-success-message">Indicates a successful or positive action.</span>
        </div>
    </div>
    <div id="alert-danger" style="display: none;">
        <div class="alert alert-danger alert-dismissible">
            <strong id="alert-danger-title">Lỗi!</strong><span id="alert-danger-message">Indicates a successful or positive action.</span>
        </div>
    </div>
    <div class="main-content pt-3 pb-2">
        <div class="row">
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header bg-footer text-light font-weight-bold"><i class="fas fa-globe-americas mr-2"></i>Tên
                        website
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3"><i class="fas fa-heading mr-2"></i><b>Tên website:</b></div>
                            <div class="col-sm-9">
                                <div class="form-group"><input type="text" class="form-control" id="web-name"
                                                               name="web-name"
                                                               value="<?php echo $this->info->websitename?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><i class="fas fa-align-left mr-2"></i><b>Mô tả ngắn:</b></div>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="short-description" name="short-description"><?php echo $this->info->description?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn bg-orange text-light" id="btnSaveFirst"><i class="fas fa-save mr-2"></i>Lưu</button>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header bg-footer text-light font-weight-bold"><i class="fas fa-home mr-2"></i>Thông tin
                        khác
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3"><i class="fas fa-image mr-2"></i><b>Tên trên hình ảnh lớn:</b></div>
                            <div class="col-sm-9">
                                <div class="form-group"><input type="text" class="form-control" id="img-title"
                                                               name="img-title" value="<?php echo $this->info->homename?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><i class="fas fa-image mr-2"></i><b>Mô tả ngắn:</b></div>
                            <div class="col-sm-9">
                                <div class="form-group"><input type="text" class="form-control" id="home-short"
                                                               name="home-short"
                                                               value="<?php echo $this->info->homeshort?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><i class="fas fa-layer-group mr-2"></i><b>Powered by:</b></div>
                            <div class="col-sm-9">
                                <div class="form-group"><input type="text" class="form-control" id="powered-by"
                                                               name="powered-by"
                                                               value="<?php echo $this->info->poweredby?>">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn bg-orange text-light" id="btnSaveSecond"><i class="fas fa-save mr-2"></i>Lưu</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body text-center">
                            <div style="font-size:40px"><i class="fas fa-feather text-findhouse"></i></div>
                            <p class="card-text text-findhouse font-weight-bold"><a href="javascript:showIntro()"
                                                                                    class="stretched-link">Sửa bài giới
                                    thiệu</a></p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body text-center">
                            <div style="font-size:40px"><i class="fas fa-question text-orange"></i></div>
                            <p class="card-text text-orange font-weight-bold"><a href="javascript:showHelp()"
                                                                                 class="stretched-link">Sửa Trợ giúp</a>
                            </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body text-center">
                            <div style="font-size:40px"><i class="fas fa-info-circle text-chiro"></i></div>
                            <p class="card-text text-chiro font-weight-bold"><a href="javascript:showLogo()"
                                                                                class="stretched-link">Sửa Logo</a></p>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header bg-footer text-light font-weight-bold"><i class="fas fa-id-badge mr-2"></i>Thông
                        tin liên hệ
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4"><i class="fas fa-map-marker-alt mr-2"></i><b>Địa chỉ:</b></div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" id="address" name="address"><?php echo $this->info->address?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><i class="fas fa-phone mr-2"></i><b>Số điện thoại:</b></div>
                            <div class="col-sm-8">
                                <div class="form-group"><input type="text" class="form-control" id="phone" name="phone"
                                                               value="<?php echo $this->info->phone?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><i class="fas fa-paper-plane mr-2"></i><b>Địa chỉ Email:</b></div>
                            <div class="col-sm-8">
                                <div class="form-group"><input type="text" class="form-control" id="email" name="email"
                                                               value="<?php echo $this->info->email?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><i class="fab fa-facebook mr-2"></i><b>Link Facebook:</b></div>
                            <div class="col-sm-8">
                                <div class="form-group"><input type="text" class="form-control" id="facebook"
                                                               name="facebook" value="<?php echo $this->info->facebook?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><i class="fab fa-instagram mr-2"></i><b>Link Instagram:</b></div>
                            <div class="col-sm-8">
                                <div class="form-group"><input type="text" class="form-control" id="instagram"
                                                               name="instagram" value="<?php echo $this->info->instagram?>"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><i class="fab fa-twitter mr-2"></i><b>Link Twitter:</b></div>
                            <div class="col-sm-8">
                                <div class="form-group"><input type="text" class="form-control" id="twitter"
                                                               name="twitter" value="<?php echo $this->info->twitter?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn bg-orange text-light" id="btnSaveThird"><i class="fas fa-save mr-2"></i>Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="edit-intro">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-feather mr-2"></i>Chỉnh sửa Giới thiệu</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="introduction">Bài viết giới thiệu</label>
                    <textarea class="form-control" rows="5" id="modal-introduction"><?php echo htmlspecialchars_decode($this->info->introduction)?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="btn-intro" id="btn-intro"><i
                            class="fas fa-save mr-2"></i>Lưu
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="edit-help">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-question mr-2"></i>Chỉnh sửa phần trợ giúp</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="introduction">Bài viết Trợ giúp:</label>
                    <textarea class="form-control" rows="5" id="modal-help"><?php echo htmlspecialchars_decode($this->info->help)?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="btn-help" id="btn-help"><i
                            class="fas fa-save mr-2"></i>Lưu
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="edit-logo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-cube mr-2"></i>Chỉnh sửa Logo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                        <p>Logo trang web:</p>
                        <p><img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/logo.png" width="48" height="48"/></p>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="customLogo" id="customLogo" accept="image/png" value="">
                            <label class="custom-file-label" for="customFile">Chọn tập tin .png</label>
                        </div>
                        <p class="mt-3">Hình ảnh lớn ở trang chủ (khuyến cáo sử dụng hình ảnh độ phân giải cao):</p>
                        <p>Slide 1:</p>
                        <p><img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/website/1.jpg" style="width:100%"/></p>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="customBanner" id="customBanner"  accept="image/jpeg">
                            <label class="custom-file-label" for="customFile">Chọn tập tin .jpg</label>
                        </div>
                        <p>Slide 2:</p>
                        <p><img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/website/2.jpg" style="width:100%"/></p>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="customBanner1" id="customBanner1" accept="image/jpeg">
                            <label class="custom-file-label" for="customFile">Chọn tập tin .jpg</label>
                        </div>
                        <p>Slide 3:</p>
                        <p><img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/website/3.jpg" style="width:100%"/></p>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="customBanner2" id="customBanner2" accept="image/jpeg">
                            <label class="custom-file-label" for="customFile">Chọn tập tin .jpg</label>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="btnUpdatePic" id="btnUpdatePic"><i class="fas fa-save mr-2"></i>Lưu
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>

</div>
<script>
    CKEDITOR.replace('modal-introduction', {
        extraPlugins: 'uploadimage',
    });
    CKEDITOR.replace('modal-help', {
        extraPlugins: 'uploadimage',
    });

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    function showIntro() {
        $('#edit-intro').modal();
    }

    function showHelp() {
        $('#edit-help').modal();
    }

    function showLogo() {
        $('#edit-logo').modal();
    }

    $("#btnSaveFirst").click(function() {
        $("#alert-success").hide();
        $("#alert-danger").hide();
        dataInput = {};
        dataInput['webName'] = $("#web-name").val();
        dataInput['webDescription'] = $("#short-description").val();

        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/saveWebsite1',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                if (JSON.parse(res).status == 'Thành công!') {
                    $("#alert-success-message").html(' Đã cập nhật thành công!');
                    $("#alert-success").show(500);
                    setTimeout(() => {
                        $("#alert-success").hide(500);
                    }, 2000);
                } else {
                    $("#alert-danger-message").html(' ' + JSON.parse(res).status);
                    $("#alert-danger").show(500);
                    setTimeout(() => {
                        $("#alert-danger").hide(500);
                    }, 2000);
                }
            }
        });
    })

    $("#btnSaveSecond").click(function() {
        $("#alert-success").hide();
        $("#alert-danger").hide();
        dataInput = {};
        dataInput['homeName'] = $("#img-title").val();
        dataInput['homeShort'] = $("#home-short").val();
        dataInput['powerBy'] = $("#powered-by").val();

        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/saveWebsite2',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                if (JSON.parse(res).status == 'Thành công!') {
                    $("#alert-success-message").html(' Đã cập nhật thành công!');
                    $("#alert-success").show(500);
                    setTimeout(() => {
                        $("#alert-success").hide(500);
                    }, 2000);
                } else {
                    $("#alert-danger-message").html(' ' + JSON.parse(res).status);
                    $("#alert-danger").show(500);
                    setTimeout(() => {
                        $("#alert-danger").hide(500);
                    }, 2000);
                }
            }
        });
    })

    $("#btnSaveThird").click(function() {
        $("#alert-success").hide();
        $("#alert-danger").hide();
        dataInput = {};
        dataInput['address'] = $("#address").val();
        dataInput['phone'] = $("#phone").val();
        dataInput['email'] = $("#email").val();
        dataInput['facebook'] = $("#facebook").val();
        dataInput['insta'] = $("#instagram").val();
        dataInput['twitter'] = $("#twitter").val();

        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/saveWebsite3',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                if (JSON.parse(res).status == 'Thành công!') {
                    $("#alert-success-message").html(' Đã cập nhật thành công!');
                    $("#alert-success").show(500);
                    setTimeout(() => {
                        $("#alert-success").hide(500);
                    }, 2000);
                } else {
                    $("#alert-danger-message").html(' ' + JSON.parse(res).status);
                    $("#alert-danger").show(500);
                    setTimeout(() => {
                        $("#alert-danger").hide(500);
                    }, 2000);
                }
            }
        });
    })

    $("#btn-intro").click(function() {
        $("#alert-success").hide();
        $("#alert-danger").hide();
        dataInput = {};
        dataInput['intro'] = CKEDITOR.instances['modal-introduction'].getData();

        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/saveWebsiteIntro',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                $('#edit-intro').modal('hide');
                if (JSON.parse(res).status == 'Thành công!') {
                    $("#alert-success-message").html(' Đã cập nhật thành công!');
                    $("#alert-success").show(500);
                    setTimeout(() => {
                        $("#alert-success").hide(500);
                    }, 2000);
                } else {
                    $("#alert-danger-message").html(' ' + JSON.parse(res).status);
                    $("#alert-danger").show(500);
                    setTimeout(() => {
                        $("#alert-danger").hide(500);
                    }, 2000);
                }
            }
        });
    });

    $("#btn-help").click(function() {
        $("#alert-success").hide();
        $("#alert-danger").hide();
        dataInput = {};
        dataInput['help'] = CKEDITOR.instances['modal-help'].getData();

        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/saveHelp',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                $('#edit-help').modal('hide');
                if (JSON.parse(res).status == 'Thành công!') {
                    $("#alert-success-message").html(' Đã cập nhật thành công!');
                    $("#alert-success").show(500);
                    setTimeout(() => {
                        $("#alert-success").hide(500);
                    }, 2000);
                } else {
                    $("#alert-danger-message").html(' ' + JSON.parse(res).status);
                    $("#alert-danger").show(500);
                    setTimeout(() => {
                        $("#alert-danger").hide(500);
                    }, 2000);
                }
            }
        });
    });
</script>