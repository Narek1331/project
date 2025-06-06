<!DOCTYPE html>
<html>
<head>
  <title>Excel Editor</title>
  <script src="https://cdn.jsdelivr.net/npm/handsontable@14.0.0/dist/handsontable.full.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.0.0/dist/handsontable.full.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    #hot {
      display: none;
    }
  </style>
</head>
<body>

  <div id="hot"></div>

  <script>
  const urlParams = new URLSearchParams(window.location.search);
  const token = urlParams.get('token');
  const userId = atob(token);

  async function checkPassword(password) {

    const response = await fetch('/api/check-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        user_id: userId,
        password: password
      })
    });

    if (!response.ok) {
      alert("Ошибка сервера при проверке пароля");
      throw new Error("Server error");
    }

    const data = await response.json();

    if (data.valid) {
      document.getElementById('hot').style.display = 'block';

      const hot = new Handsontable(document.getElementById('hot'), {
        data: @json($data),
        rowHeaders: true,
        colHeaders: true,
        licenseKey: 'non-commercial-and-evaluation'
      });
    } else {
      alert("Неверный пароль!");
    }
  }

  // Получаем пароль через prompt и запускаем проверку
  const password = prompt("Введите пароль:");
  if (password !== null) {
    checkPassword(password);
  } else {
    alert("Пароль не введён");
  }
</script>

</body>
</html>
