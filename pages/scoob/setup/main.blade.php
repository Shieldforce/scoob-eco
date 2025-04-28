<main class="form-signin w-100 m-auto">
    <form
            method="POST"
            action="{{ route("pages.scoob.setup.run") }}"
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
        <button class="btn btn-primary w-100 py-2" type="submit">Iniciar Instalação</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; {{ \ScoobEco\Core\Config::get("system.name") }}</p>
    </form>
</main>