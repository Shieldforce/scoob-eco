<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sem conteúdo!</title>
    <link rel="stylesheet" href="{{ asset("css/home.css") }}">
</head>
<body>
<div class="container">
    <h1>Comece o desenvolvimento!</h1>
    <hr>
    <div class="div-link">
        <?php if (isset($user->id)): ?>
            <a
                class="link"
                href="{{ route("pages.scoob.login") }}"
            >
                Logar no Scoob-Eco
            </a>
        <?php else: ?>
            <a
                class="link"
                href="{{ route("pages.scoob.setup") }}"
            >
                Instalar o Scoob-Eco
            </a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>