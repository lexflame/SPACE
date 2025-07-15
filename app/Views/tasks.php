<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MakerTask</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
      background-color: #343a40;
      color: white;
    }

    .toolbar {
      height: 60px;
      background-color: #212529;
      display: flex;
      align-items: center;
      padding: 0 20px;
    }

    .logo {
      display: flex;
      align-items: center;
    }

    .logo svg {
      height: 36px;
      width: 36px;
      margin-right: 10px;
      fill: #f8f9fa;
    }

    .content {
      height: calc(100% - 60px);
      overflow-y: auto;
      padding: 20px;
    }
  </style>
</head>
<body>

  <!-- Верхний тулбар -->
  <div class="toolbar">
    <div class="logo">
      <!-- Кастомный SVG логотип -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
        <!-- Внешняя шестерёнка -->
        <path d="M32 4a28 28 0 1 0 0.001 56.001A28 28 0 0 0 32 4zm0 4a24 24 0 1 1 0 48 24 24 0 0 1 0-48z" fill="#f8f9fa"/>
        <!-- Буква M -->
        <path d="M22 44V20h4l6 10 6-10h4v24h-4V28l-6 10-6-10v16h-4z" fill="#17a2b8"/>
        <!-- Карандаш в центре -->
        <path d="M31 24l2 2-10 10-2-2zM22 38l2 2 2-2-2-2z" fill="#ffc107"/>
      </svg>
      <span class="h5 mb-0 font-weight-bold text-light">MakerTask</span>
    </div>
  </div>

  <!-- Контент с прокруткой -->
  <div class="content">
    <p>Контент со скроллом...</p>
    <div style="height: 1500px;"></div>
  </div>

</body>
</html>
