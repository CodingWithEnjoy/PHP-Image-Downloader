<?php
session_start();
include("sqlak.class.php");
$db = new sqlak(["host" => "localhost"], "darsijat");
if (isset($_GET['logout'])) {
    session_unset();
    header('location:http://localhost/');
}
$errors = "";
function error($error): void
{
    global $errors;
    $errors = '<div id="error">' . $error . '</div>';
}

if (!isset($_SESSION['map'])) {
    if (isset($_POST['id']) && isset($_POST['pass'])) {
        if (1 == $db->count('maps', 'id = "' . $_POST['id'] . '"')) {
            if ($_POST['pass'] == $db->give('maps', 'id = "' . $_POST['id'] . '"')[0]['pass']) {
                $_SESSION['map'] = $_POST['id'];
                $_SESSION['admin'] = 'false';
                header('location:http://localhost/');
            } elseif ($_POST['pass'] == $db->give('maps', 'id = "' . $_POST['id'] . '"')[0]['admin']) {
                $_SESSION['map'] = $_POST['id'];
                $_SESSION['admin'] = 'true';
                header('location:http://localhost/');
            } else {
                error('رمز ورود به نقشه اشتباه است :(');
            }
        } else {
            error('آیدی نقشه اشتباه است :(');
        }
    }
}
?>
<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>درسیجات</title>
    <link rel="stylesheet" href="Vazirmatn-RD-FD-font-face.css">
    <style>
        * {
            box-sizing: border-box;
            margin: unset;
            padding: unset;
            font-family: "Vazirmatn RD FD", sans-serif;
            direction: rtl;
        }

        #form[main] {
            text-align: center;
            padding: 20px;
            max-width: 500px;
            margin: auto;
        }

        footer[main] ul, footer[main] li {
            display: inline-block;
        }

        footer[main] li {
            padding: 10px;
        }

        a {
            user-select: none;
            cursor: pointer;
            color: #0368bd;
            text-decoration: unset;
        }

        a:hover, a:active {
            text-decoration: underline;
        }

        a:active {
            color: #04569a;
        }

        footer[main] {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
        }

        input, button, textarea {
            transition: .2s;
            padding: 7px 13px 5px;
            border: 0;
            outline: 0;
            border-radius: 10px;
        }

        input, textarea {
            box-shadow: inset rgba(0, 0, 0, .3) 0 0 0 1px;
        }

        button {
            cursor: pointer;
            color: white;
            background: #0368bd;
        }

        button:active {
            background: #0c5c9f;
        }

        input:hover, textarea:hover {
            box-shadow: inset rgba(0, 0, 0, .4) 0 0 0 2px;
        }

        input:focus, textarea:focus {
            box-shadow: inset rgb(3, 104, 188) 0 0 0 2px, rgba(3, 104, 188, .4) 0 0 0 3px;
        }

        button:focus {
            box-shadow: rgba(3, 104, 188, .5) 0 0 0 2px !important;
        }

        button[main] {
            border-radius: 100px;
            width: 200px;
            display: block;
            margin: 20px auto auto;
        }

        button[main]:hover {
            box-shadow: rgba(3, 104, 189, 0.65) 0 1px 15px;
        }

        #active_form {

        }

        input[readonly] {
            background: #dcdcdc;
            color: gray;
            text-align: center;
            box-shadow: unset !important;
        }

        .transparent {
            color: gray;
            box-shadow: unset !important;
            background: rgba(0, 0, 0, .1);
        }

        .transparent:focus {
            box-shadow: unset !important;
        }

        .transparent:hover {
            background: rgba(0, 0, 0, .2);
        }

        .transparent:active {
            background: rgba(0, 0, 0, .3) !important;
        }

        #error {
            background: #f9c0c3;
            color: #74575a;
            margin-top: 10px;
            margin-bottom: 15px;
            padding: 8px 10px 5px;
            font-size: .8rem;
            border-radius: 5px;
            display: inline-block;
        }

        #nav {
            width: 100%;
            padding: 9px 17px;
            box-shadow: rgba(0, 0, 0, .15) 0 2px 10px;
        }

        button.outlined {
            box-shadow: #0c5c9f 0 0 0 1px inset;
            background: white;
            color: #0c5c9f;
            font-weight: 500;
        }
        button.outlined:hover{
            box-shadow: #0c5c9f 0 0 0 1px inset,rgba(3, 104, 189, 0.65) 0 1px 15px ;
        }
        button.outlined:active{
            background: rgba(12, 92, 159, 0.2);
        }
        button.outlined:focus{
            box-shadow: #0c5c9f 0 0 0 1px inset,rgba(3, 104, 189, 0.65) 0 1px 15px ,rgba(3, 104, 188, .3) 0 0 0 2px !important;
        }
    </style>
    <script>
        function goMap(mapId) {
            document.getElementById('active_form').innerHTML = '<form method="post"><div style="font-size: .8rem;"></div><p style="font-size: .8rem;font-weight: 500;margin-top:15px;margin-bottom: 15px">شما در حال ورود به نقشه زیر هستید : <span style="color: #0c5c9f">' + mapId + '</span></p><input hidden name="id" value="' + mapId + '"><input style="padding: 12px 20px 11px;width: 250px" type="password" name="pass" placeholder="رمز خود را اینجا وارد کنید ..."><button main type="submit">بزن بریم</button><button main class="transparent" onclick="event.preventDefault();location.reload()" style="margin-top: 10px">بازگشت</button></div></form>';
        }
    </script>
</head>
<body>
<div main id="form">
    <h1 style="font-size: 2.3rem ;margin-top: 30px;font-weight: 900">درسیجات</h1>
    <?php

    if (!isset($_SESSION['map'])) {
        echo $errors;
        ?>
        <p style="font-size: .9rem;margin: 5px 10px 0;">درسیجات به زبان ساده یک محیط برای ارائه محتوای آموزشی است. عموما
            آموزشگاه ها برای اطلاع رسانی و ارائه جزوه ها و
            محتوای افزوده خود یک کانال ایجاد میکنند، این روش باعث سردرگمی دانش آموزان برای پیدا کردن محتوای مورد نظر بین
            هزاران
            پیام اطلاع رسانی میشود. ولی در درسیجات هر آموزشگاه میتواند محتوای خود را با دسته بندی مناسب به دانش آموزان
            خود
            ارائه
            دهد و یک آرشیو مناسب از محتوای خود ایجاد کند :)</p>
        <div id="active_form">
            <input style="margin-top: 20px;padding: 12px 20px 11px;width: 250px" type="text" id="map_id"
                   placeholder="آیدی نقشه ...">
            <p style="font-size: .8rem;font-weight: 500;margin-top:15px">نکته : آیدی نقشه را باید از آموزشگاه خود
                بگیرید</p>
            <button main type="submit" onclick="goMap(document.getElementById('map_id').value)">ورود</button>
        </div>

    <?php } else {
        ?>
        <h3 style="color: #086bbe"><?php if ($_SESSION['admin'] == "true") {
                echo json_decode(file_get_contents('maps/' . $_SESSION['map'] . '/manifest.json'), true)['admin'];
            } else {
                echo "دانش آموز";
            } ?> عزیز خوش آمدید</h3>
        <p style="font-size: .9rem;margin: 15px 10px 0;">
            به داشبورد درسیجات خودتان خوش آمدید ، اینجا میتوانید به نقشه آموزشگاه خودتان دست بیابید و تنظیمات نقشه خود
            را ( اگر ادمین هستی ) تغییر دهید
        </p>
        <?php
        if ($_SESSION['admin'] == 'true') {
            ?>
            <button style="padding: 13px 11px 11px;border-radius: 10px;" class="outlined" main>تنظیمات</button>
            <?php
        }
    } ?>
    <footer main>
        <ul>
            <li><a>درباره ما</a></li>
            <li><a>نحوه کار</a></li>
            <li><a>ثبت نقشه</a></li>
            <?php if (isset($_SESSION['map'])) {
                ?>
                <li><a href="?logout">خروج</a></li>
                <?php
            } ?>
        </ul>
    </footer>
</div>
</body>
</html>