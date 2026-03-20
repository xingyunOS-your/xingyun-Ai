<?php
$error = "";
$success = "";

if ($_POST) {
    $user = trim($_POST['user']);
    $pwd = trim($_POST['pwd']);

    if (!$user || !$pwd) {
        $error = "账号密码不能为空";
    } else {
        $file = "xingyun.txt";
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        $exists = false;

        foreach ($lines as $line) {
            $arr = explode("|", $line);
            if ($arr[0] == $user) {
                $exists = true;
                break;
            }
        }

        if ($exists) {
            $error = "账号已存在";
        } else {
            $fp = fopen($file, "a");
            fwrite($fp, $user . "|" . $pwd . "\n");
            fclose($fp);
            $success = "注册成功！去登录";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>星陨AI - 注册</title>
    <style>
        body{background:#0a0a0d;color:#fff;font-family:monospace;text-align:center;padding-top:50px;}
        .box{background:#101015;padding:30px;border-radius:10px;display:inline-block;}
        input{margin:5px;padding:10px;width:200px;background:#14141b;color:#fff;border:1px solid #2a2a35;border-radius:6px;}
        button{padding:10px 20px;background:#00cc99;color:#fff;border:none;border-radius:6px;margin-top:10px;}
        a{color:#00ffcc;}
        .red{color:red;}
        .green{color:lime;}
    </style>
</head>
<body>
<div class="box">
    <h2>[ 星陨AI 注册 ]</h2>
    <?php if($error) echo "<p class='red'>$error</p>"; ?>
    <?php if($success) echo "<p class='green'>$success</p>"; ?>
    <form method="post">
        <input name="user" placeholder="账号"><br>
        <input name="pwd" type="password" placeholder="密码"><br>
        <button>注册</button>
    </form>
    <br>
    <a href="login.php">已有账号？去登录</a>
</div>
</body>
</html>
