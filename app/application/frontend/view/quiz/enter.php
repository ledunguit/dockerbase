<div class="container-fluid">
    <div class="main-content p-5"><h2 class="d-flex justify-content-center">Nhập mã đề</h2>
        <div class="d-flex col-sm-8 justify-content-center">
            <form action="#" id="code-form" method="post" class="input-group input-group-lg mb-3">
                <input type="text" class="form-control" placeholder="Nhập code của đề thi..." maxlength="8" id="code" name="code">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-warning" id="btnSubmitCode">Truy cập</button>
                </div>
            </form>
        </div>
        <div class="alert alert-danger" id="result" style="display: none"></div>
    </div>
</div>
<script type="text/javascript">
    var isValid;
    $("#btnSubmitCode").click(function(event) {
        isValid = false;
        var codeValue = $('#code').val();
        $.post("../ajax/enterByCode", {code:codeValue}, function(data) {
            if (data == "null") {
                $("#result").html("Không tìm thấy đề thi này!");
                $("#result").css("display", "block");
            } else {
                isValid = true;
            }
            if (isValid) {
                window.location.replace("/quiz/enroll/" + data);
            }
        });
        event.preventDefault();
    })
</script>