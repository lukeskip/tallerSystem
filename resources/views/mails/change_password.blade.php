<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Hola {{ $user->name }},

    Has recibido este correo electrónico porque se ha creado una cuenta para ti en nuestro sitio web. Para establecer una nueva contraseña, haz clic en el siguiente enlace:

    {{ route('password.reset', ['token' => $user->reset_token]) }}

    Si no solicitaste este cambio de contraseña, puedes ignorar este correo electrónico. Tu contraseña seguirá siendo la misma.
    
</body>
</html>
