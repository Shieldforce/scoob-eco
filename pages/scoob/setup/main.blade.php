<main class="form-setup w-100 m-auto">
    <form
            method="POST"
            action="{{ route("pages.scoob.setup.run", ["type" => 1]) }}"
            id="stepSetupScoobEco"
            enctype="multipart/form-data"
    >
        <input type="hidden" name="_token" value="teste">
        <img
                class="mb-1"
                src="{{ asset('imgs/logo-trans-scoob.png') }}"
                alt="Logo Scoob"
                width="200"
                height="150"
        >
        <div class="container-setup">
            <span class="alert-light">
                <i class="fa fa-cogs"></i>
                Aguardando inicio da instalação!
            </span>
        </div>

        <button
            class="btn btn-primary w-100 py-2"
            type="submit"
            id="button-start-setup"
        >
            Iniciar Instalação
        </button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; {{ \ScoobEco\Core\Config::get("system.name") }}</p>
    </form>
</main>