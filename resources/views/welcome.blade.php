<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arabito</title>
    <style type="text/css">
        html,body{
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .container{
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo{
            width: 700px;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <img src="{{ asset('images/logo.png') }}" alt="" class="logo">
    </div>

</body>
</html>