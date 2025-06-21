<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cashier Application</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #4FC3F7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      text-align: center;
      width: 90%;
      max-width: 400px;
    }
    h2 {
      color: white;
      font-size: 30px;
      margin-bottom: 30px;
      margin-left: 20px;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 20px;
      margin: 10px 0;
      border: none;
      border-radius: 30px;
      font-size: 16px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    button {
      width: 80%;
      padding: 15px;
      background-color: #666;
      color: white;
      font-weight: bold;
      font-size: 16px;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      margin-top: 20px;
      margin-left: 20px;
    }
    button:hover {
      background-color: #555;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>CASHIER APPLICATION</h2>
    <form action="proses_login.php" method="POST">
      <div>
        <input type="text" name="idAdmin" placeholder="Nomor ID . . ." required>
      </div>
      <div>
        <input type="password" name="password" placeholder="Password . . ." required>
      </div>
      <button type="submit">LOGIN</button>
    </form>
  </div>
</body>
</html>
