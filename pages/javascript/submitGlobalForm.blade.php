<script>
    document.querySelector("#formGlobalSubmit").addEventListener("submit", function (e) {
        e.preventDefault();

        loadingScoob(
            'Salvando os dados ...',
            'Aguarde para que possamos salvar todos os dados!',
        );

        const formData = new FormData(this);

        fetch(this.action, {
            method: this.method,
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(async response => {
                const contentType = response.headers.get("content-type");
                if (response.ok && contentType && contentType.includes("application/json")) {
                    const data = await response.json();
                    let msg = data.message ?? 'Dados salvos com sucesso!';
                    Swal.close();
                    successScoob(
                        'Sucesso!',
                        msg,
                    );
                } else {
                    const errorText = await response.text();
                    Swal.close();
                    let msg = errorText.message ?? "Erro ao salvar os dados!";
                    errorScoob(
                        'Erro@!',
                        msg
                    );
                    console.error("Resposta inesperada:", errorText);
                }
            })
            .catch(error => {
                Swal.close();
                var msg = error ?? "Erro ao salvar os dados!";
                errorScoob(
                    'Erro@!',
                    msg
                );
                console.error(error);
            });
    });

</script>
