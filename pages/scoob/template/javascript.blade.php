<script
        defer
        src="{{ asset('/bootstrap-examples/assets/dist/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset("/bootstrap-examples/assets/js/color-modes.js") }}"></script>

<script>

    function boostrapSpinnerBorder(id, type, text, remove) {
        /*
        Colors: primary, secondary, success, danger,
        warning, info, light, dark
        */

        if (remove) {
            const element = document.getElementById(id);
            if (element) {
                element.remove();
            }
            return ``;
        }

        return `<div id="${id}" class="d-flex align-items-center gap-2 mb-1">
                  <div class="spinner-border text-${type}" role="status"></div>
                  <strong>${text}...</strong>
                </div>`;
    }

    function boostrapSpinnerGrow(id, type, text, remove) {
        /*
            Colors: primary, secondary, success, danger,
            warning, info, light, dark
        */

        if (remove) {
            const element = document.getElementById(id);
            if (element) {
                element.remove();
            }
            return ``;
        }

        return `<div id="${id}" class="d-flex align-items-center gap-2 mb-1">
                  <div class="spinner-grow text-${type}" role="status"></div>
                  <strong>${text}...</strong>
                </div>`;
    }

    function boostrapCheckText(id, type, text, size, remove) {
        /*
        Colors: primary, secondary, success, danger,
        warning, info, light, dark
        */

        if (remove) {
            const element = document.getElementById(id);
            if (element) {
                element.remove();
            }
            return ``;
        }

        let icon = "fa-check-circle";

        if (type === 'danger') {
            icon = "fa-times-circle";
        }

        return `<div id="${id}" class="d-flex align-items-center gap-2 mb-1">
                  <div class="text-${type} fa ${icon} fa-${size}x" role="status"></div>
                  <strong>${text}...</strong>
                </div>`;
    }

    async function submitFormDataCustomSetup(action, method, formElement, itemElement, setupElementObj) {
        const formData = new FormData(formElement);

        try {
            const response = await fetch(action, {
                method: method,
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            boostrapSpinnerBorder(setupElementObj.id, 'remover', '', true);
            const contentType = response.headers.get("content-type");

            if (response.ok && contentType && contentType.includes("application/json")) {
                const data = await response.json();
                let msg = data.message ?? 'Deu tudo certo.';
                itemElement.innerHTML += boostrapCheckText(setupElementObj.id + '-new', 'success', msg, '2');
                return true;
            } else {
                const errorText = await response.json();
                let msg = errorText.message || 'Ocorreu um erro.';
                console.error("Resposta inesperada:", errorText);
                itemElement.innerHTML += boostrapCheckText(setupElementObj.id + '-new', 'danger', msg, '2');
                return false;
            }
        } catch (error) {
            var msg = error ?? "Ocorreu um erro grave.";
            console.error(error);
            boostrapSpinnerBorder(setupElementObj.id, 'remover', '', true);
            itemElement.innerHTML += boostrapCheckText(setupElementObj.id + '-new', 'danger', msg, '2');
            return false;
        }
    }

</script>