<main class="auth-page">
    <section class="auth">
        <article>
            <div class="form-wrapper">
                <h1>
                    Inscription
                </h1>

                <form action="" method="POST">
                    <?= \App\Core\Http\Csrf::template() ?>

                    <div class="form-group">
                        <label for="username">Pseudo</label>
                        <input type="text" name="username" id="username">
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" name="email" id="email">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password">
                    </div>

                    <button>
                        S'inscrire
                    </button>
                </form>

                <p>
                    Déjà inscrit ? <a href="/auth/login">Connectez-vous</a>
                </p>
            </div>
        </article>

        <aside>
            <img class="book-cover" src="/img/auth.png" alt="">
        </aside>
    </section>
</main>
