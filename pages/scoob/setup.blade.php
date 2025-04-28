<!doctype html>
<html
    lang="pt-BR"
    data-bs-theme="auto"
>
@include("scoob.template.head")
<body class="d-flex align-items-center py-4 bg-body-tertiary">
    @include("scoob.template.svg")
    @include("scoob.template.select-theme")
    @include("scoob.setup.main")
    @include("scoob.template.javascript")
    @include("javascript.StepSetupScoobEco")
    @include("javascript.sweetalert2")
</body>
</html>
