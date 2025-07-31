<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Координатные линейки</title>
    <link rel="stylesheet" href="/assets/css/dev_styles.css">
</head>
<body>
    <div id="coordinate-ruler"></div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/ruler.js"></script>
    <script>
        $(document).ready(function() {
            $('#coordinate-ruler').coordinateRuler();
        });
    </script>
</body>
</html>