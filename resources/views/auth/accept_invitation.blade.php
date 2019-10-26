<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .my-form {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .my-form .row {
            margin-left: 0;
            margin-right: 0;
        }

        .login-form {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .login-form .row {
            margin-left: 0;
            margin-right: 0;
        }
    </style>
</head>

<body>
    <main class="my-form">
        <div class="cotainer">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center">Xin chào {{ $email }}, để tham gia ADCRM, hãy
                            đăng
                            kí tài khoản của bạn</div>
                        <div class="card-body">
                            <form name="my-form" onsubmit="return validform()" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Họ tên</label>
                                    <div class="col-md-6">
                                        <input type="text" id="full-name" class="form-control" name="name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="phone_number" class="col-md-4 col-form-label text-md-right">Số điện
                                        thoại</label>
                                    <div class="col-md-6">
                                        <input type="text" id="phone-number" class="form-control" name="phone_number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="user_name" class="col-md-4 col-form-label text-md-right">Mật
                                        khẩu</label>
                                    <div class="col-md-6">
                                        <input type="password" id="password" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="user_name" type="password"
                                        class="col-md-4 col-form-label text-md-right">Nhập lại mật
                                        khẩu</label>
                                    <div class="col-md-6">
                                        <input type="password" id="password-confirmation" class="form-control"
                                            name="password_confirmation">
                                    </div>
                                </div>
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Đăng kí tài khoản
                                    </button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        function validform() {

var a = document.forms["my-form"]["name"].value;
var b = document.forms["my-form"]["password"].value;
var c = document.forms["my-form"]["password_confirmation"].value;
var d = document.forms["my-form"]["phone_number"].value;
if (a==null || a=="")
{
    alert("Hãy nhập họ tên của bạn");
    return false;
}
else if(d==null||d==""){
    alert("Hãy nhập số điện thoại")
    return false;
}
else if(!d.match(
            /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/
        )){
        
                alert("Số điện thoại không đúng")
    return false;
}
else if (b==null || b=="")

{
    alert("Hãy nhập mật khẩu");
    return false;
}
else if(b.length<8){
    alert("Mật khẩu tối thiểu 8 kí tự")
    return false;
}
else if(c==null||c==""){
    alert("Hãy nhập lại mật khẩu")
    return false;
}
else if( b!=c){
    alert("Mật khẩu chưa trùng khớp")
    return false;
}


}
    </script>

</body>

</html>