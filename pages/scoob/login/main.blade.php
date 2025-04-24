<main class="form-signin w-100 m-auto">
    <form
            method="POST"
            action="{{ route("pages.scoob.login") }}"
            onsubmit="submitGlobalForm(this);"
    >
        <img
                class="mb-4"
                src="{{ asset('imgs/logo-trans-scoob.png') }}"
                alt="Logo Scoob"
                width="200"
                height="150"
        >
        <h1 class="h3 mb-3 fw-normal">Acessar ScoobEco</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">E-mail</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Senha">
            <label for="floatingPassword">Senha</label>
        </div>

        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
            <label class="form-check-label" for="checkDefault">
                Lembrar me
            </label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Acessar</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; {{ \ScoobEco\Core\Config::get("system.name") }}</p>
    </form>
</main>