<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['login'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>星陨AI · 游戏大厅</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Consolas, monospace;}
body{background:#0a0a0d;color:#e0e0e0;padding:16px;max-width:800px;margin:0 auto;}
.hero{text-align:center;margin-bottom:20px;}
.hero h1{font-size:22px;color:#00ffcc;text-shadow:0 0 8px #00ffcc;}
.hero p{color:#888;font-size:13px;}
.welcome{color:#00ffcc;margin-bottom:10px;text-align:center;}
.game-box{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;}
.game{background:#101015;padding:15px;border-radius:8px;text-align:center;}
.game button{padding:10px;background:#00cc99;color:#fff;border:none;border-radius:6px;width:100%;margin-top:8px;cursor:pointer;}
.console{background:#101015;border:1px solid #2a2a35;border-radius:10px;height:40vh;padding:14px;overflow-y:auto;line-height:1.5;margin-bottom:10px;}
.msg-user{color:#72aaff;margin:8px 0;}
.msg-bot{color:#00ffcc;margin:8px 0;}
.input-box{display:flex;gap:8px;}
input{flex:1;background:#14141b;color:#fff;border:1px solid #2a2a35;border-radius:8px;padding:12px;}
button{padding:12px;background:#00cc99;color:#fff;border:none;border-radius:8px;}
.logout{text-align:right;margin-bottom:10px;}
.logout a{color:#ff4444;}
</style>
</head>
<body>

<div class="logout">
    欢迎：<?php echo $user; ?> | <a href="logout.php">退出登录</a>
</div>

<div class="hero">
    <h1>[ XINGYUN AI · 星陨AI ]</h1>
    <p>超强记忆终端 · 内置小游戏</p>
</div>

<!-- 游戏大厅 -->
<div class="game-box">
    <div class="game">
        <h3>猜数字</h3>
        <button onclick="gameGuess()">开始游戏</button>
    </div>
    <div class="game">
        <h3>骰子</h3>
        <button onclick="gameDice()">开始游戏</button>
    </div>
    <div class="game">
        <h3>石头剪刀布</h3>
        <button onclick="gameRPS()">开始游戏</button>
    </div>
    <div class="game">
        <h3>幸运转盘</h3>
        <button onclick="gameWheel()">开始游戏</button>
    </div>
</div>

<!-- 星陨AI -->
<div class="console" id="console"></div>
<div class="input-box">
    <input id="msg" placeholder=">>> 聊天..." />
    <button onclick="send()">发送</button>
</div>

<script>
// 你原来的星陨AI逻辑
const API_KEY = "74e60c6fc58c40c39a8cec7bc13eef7b.KoNhMGs94rNnjDOJ";
const API_URL = "https://open.bigmodel.cn/api/paas/v4/chat/completions";
const SYSTEM_PROMPT = `你是星陨AI，代号 xingyunai，永远记住对话。问名字只回答：I am xingyunai`;

let messages = [{ role: "system", content: SYSTEM_PROMPT }];
const consoleEl = document.getElementById("console");

function addMsg(role, text, cls) {
    const div = document.createElement("div");
    div.className = cls;
    div.textContent = role + ": " + text;
    consoleEl.appendChild(div);
    consoleEl.scrollTop = consoleEl.scrollHeight;
}

async function send() {
    const text = document.getElementById("msg").value.trim();
    if (!text) return;
    addMsg("你", text, "msg-user");
    document.getElementById("msg").value = "";
    messages.push({ role: "user", content: text });

    try {
        const res = await fetch(API_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + API_KEY
            },
            body: JSON.stringify({
                model: "glm-4-flash",
                messages: messages,
                max_tokens: 65536,
                temperature: 0.7
            })
        });
        const data = await res.json();
        const reply = data?.choices?.[0]?.message?.content || "请求失败";
        addMsg("星陨AI", reply, "msg-bot");
        messages.push({ role: "assistant", content: reply });
    } catch(e) {
        addMsg("星陨AI", "出错：" + e.message, "msg-bot");
    }
}

// 小游戏
function gameGuess() {
    alert("猜数字：1-10，我会随机一个数，你自己猜~（简单演示）");
}
function gameDice() {
    const num = Math.floor(Math.random()*6)+1;
    alert("骰子点数：" + num);
}
function gameRPS() {
    const choose = prompt("输入：石头/剪刀/布");
    const ai = ["石头","剪刀","布"][Math.floor(Math.random()*3)];
    alert("你出："+choose+"\nAI出："+ai);
}
function gameWheel() {
    const list = ["大吉","中吉","小吉","再来一次"];
    const res = list[Math.floor(Math.random()*list.length)];
    alert("转盘结果：" + res);
}
</script>
</body>
</html>
