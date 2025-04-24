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

                    console.log(data);
                    Swal.close();
                    successScoob(
                        'Sucesso!',
                        'Dados salvos com sucesso!',
                    );
                } else {
                    const errorText = await response.text();
                    Swal.close();
                    errorScoob(
                        'Erro@!',
                        'Erro ao salvar os dados!',
                    );
                    console.error("Resposta inesperada:", errorText);
                }
            })
            .catch(error => {
                Swal.close();
                errorScoob(
                    'Erro@!',
                    'Erro ao salvar os dados!',
                );
                console.error(error);
            });
    });

</script>
