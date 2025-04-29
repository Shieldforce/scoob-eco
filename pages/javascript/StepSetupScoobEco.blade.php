<script>
    document.querySelector("#stepSetupScoobEco").addEventListener("submit", async function (e) {
        e.preventDefault();

        const buttonStart = document.getElementById("button-start-setup");
        buttonStart.disabled = true;

        const container_setup = document.getElementsByClassName("container-setup");
        for (const item of container_setup) {
            const step1 = await creatingTablesSetup(this, item);
            let step2 = false;
            if (step1) {
                step2 = await insertDataInTablesSetup(this, item);
            }

            if (step2) {
                successScoob(
                    'Legal!',
                    'Em 5 segundos vamos te redirecionar para o login scoob!',
                    false
                );
                setTimeout(function () {
                    document.location = "/scoob-eco/admin/login";
                }, 5000);
            }

            if(step2 === false) {
                errorScoob(
                    'Ops!',
                    'Erro ao instalar scoob-eco!',
                    true
                );
            }

            buttonStart.disabled = false;
        }
    });

    async function creatingTablesSetup(formElement, itemElement) {
        let action = formElement.action;
        let newType = 1;
        let newAction = action.replace(/\/[^\/]+$/, '/' + newType);
        let spinnerObj = {
            id: 'setup-1'
        };
        itemElement.innerHTML = "";
        itemElement.innerHTML += boostrapSpinnerBorder(spinnerObj.id, 'success', 'Criando tabelas');
        await new Promise(resolve => setTimeout(resolve, 5000));
        return await submitFormDataCustomSetup(
            newAction,
            formElement.method,
            formElement,
            itemElement,
            spinnerObj,
        );
    }

    async function insertDataInTablesSetup(formElement, itemElement) {
        let action = formElement.action;
        let newType = 2;
        let newAction = action.replace(/\/[^\/]+$/, '/' + newType);
        let spinnerObj = {
            id: 'setup-2'
        };
        itemElement.innerHTML += boostrapSpinnerBorder(spinnerObj.id, 'success', 'Populando tabelas');
        await new Promise(resolve => setTimeout(resolve, 5000));
        return await submitFormDataCustomSetup(
            newAction,
            formElement.method,
            formElement,
            itemElement,
            spinnerObj,
        );
    }
</script>
