<html>
<head>
<title>Login</title>
</head>
<body>
<form action="?" method="POST" name="f">
    <label for="username">user</label>
    <input id="username" type="text" name="username">
    <label for="password">password</label>
    <input id="password" type="password" name="password">
    <input name="AuthState" type="hidden" value="<?php echo htmlspecialchars($authstate) ?>">
    <button id="regularsubmit" class="btn"
            onclick="this.value='not translated ({login:processing);';
                this.disabled=true; this.form.submit(); return true;" tabindex="6">
        Login
    </button>
</form>
</body>
</html>