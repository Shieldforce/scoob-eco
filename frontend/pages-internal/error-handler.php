<?php
/** @var \Throwable $e */
/** @var \ScoobEco\Enum\ErrorType $type */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>[Debug: Erro de sistema]</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css">
    <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
            rel="stylesheet"
    >
    <!-- Fonte manuscrita do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Caveat&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="flex-container">
        <div class="box">
            <h2 class="title-error-h2 fonte-manu">
                <i class="fas fa-exclamation-triangle"></i>
                <?= $type->value ?>
            </h2>
        </div>
        <div class="box">
            <h2 class="title-error-h2 fonte-manu">Código: <?= $e->getCode() ?></h2>
        </div>
        <div class="box2">
            <h2 class="title-error-h2 fonte-manu">
                User System -
                <i class="fa fa-users"></i>
            </h2>
        </div>
    </div>
    <hr>
    <div class="linha-error-code-1">
        <p>
            <strong class="title-cor-white-1">Message :</strong>
            <a
                    class="title-error-a"
                    href="https://google.com/search?q=<?= $e->getMessage() ?>"
                    target="_blank"
            >
                <?= $e->getMessage() ?>
            </a>
            <strong class="title-cor-white-1">
                <i class="fas fa-search"></i>
                Clique no link do erro para buscar no google.
            </strong>
        </p>
        <p><strong class="title-cor-white-1">Arquivo :</strong> <?= $e->getFile() ?></p>
        <p>
            <strong class="title-cor-white-1">Linha :</strong>
            <strong class="fonte-2">
                <?= $e->getLine() ?>
            </strong>
        </p>
        <button id="btn-print" class="btn btn-danger">
            <i class="fas fa-bug"></i> Relatar Bug
        </button>
    </div>
    <hr>
    <div class="flex-container mt-2">
        <div class="box">
            <h2 class="title-error-h2 fonte-manu">
                <i class="fas fa-exclamation-circle"></i>
                Trilha de erros
            </h2>
        </div>
        <div class="box">
            <h2 class="title-error-h2 fonte-manu">-</h2>
        </div>
        <div class="box2">
            <h2 class="title-error-h2 fonte-manu">
                -
                <i class="fa fa-cogs"></i>
            </h2>
        </div>
    </div>
    <hr>
    <?php foreach ($e->getTrace() as $indexTrace => $trace): ?>
        <div class="linha-error-code-2">
            <p>
                <strong class="title-cor-white-1">Método :</strong>
                <?= $trace["function"]; ?>
            </p>
            <p><strong class="title-cor-white-1">Arquivo :</strong> <?= $trace["file"] ?></p>
            <p><strong class="title-cor-white-1">Classe :</strong> <?= $trace["class"] ?></p>
            <p>
                <strong class="title-cor-white-1">Linha :</strong>
                <strong class="fonte-2">
                    <?= $trace["line"]; ?>
                </strong>
            </p>
        </div>
        <hr>
    <?php endforeach; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    /*document.getElementById("btn-print").addEventListener("click", function () {
        html2canvas(document.body).then(function (canvas) {
            let link = document.createElement("a");
            link.download = "screenshot.png";
            link.href = canvas.toDataURL("image/png");
            link.click();
        });
    });*/

    document.getElementById("btn-print").addEventListener("click", function () {
        html2canvas(document.body).then(function (canvas) {
            // Converte o canvas para base64
            const imageData = canvas.toDataURL("image/png");

            // Cria um form data
            const formData = new FormData();
            formData.append("screenshot", imageData);

            // Envia para o backend
            fetch("backend.php", {
                method: "POST",
                body: formData,
            })
                .then(response => response.text())
                .then(result => {
                    console.log("Print enviado com sucesso:", result);
                    //alert("Print enviado com sucesso!");
                })
                .catch(error => {
                    console.error("Erro ao enviar o print:", error);
                    //alert("Erro ao enviar o print.");
                });
        });
    });
</script>
</body>
</html>
