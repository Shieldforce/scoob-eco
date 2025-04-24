<script>

    function loadingScoob(
        title = 'Sem título',
        html = "<span>Sem descrição</span>",
        timer = null,
    ) {
        Swal.fire({
            title: title,
            html: html,
            timer: timer,
            timerProgressBar: true,
            theme: localStorage.getItem("theme") || "light",
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    function successScoob(
        title = 'Sem título',
        html = "<span>Sem descrição</span>",
    ) {
        Swal.fire({
            title: title,
            html: html,
            theme: localStorage.getItem("theme") || "light",
            icon: "success"
        });
    }

    function errorScoob(
        title = 'Sem título',
        html = "<span>Sem descrição</span>",
    ) {
        Swal.fire({
            title: title,
            html: html,
            theme: localStorage.getItem("theme") || "light",
            icon: "error",
        });
    }

</script>
