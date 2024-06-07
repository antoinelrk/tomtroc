<main class="auth-page">
    <section class="auth">
        <article>
            <div class="form-wrapper">
                <h1>
                    Connection
                </h1>
                <form action="/auth/login" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" name="email" id="email">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password">
                    </div>

                    <button>
                        Se connecter
                    </button>
                </form>

                <p>
                    Pas de compte ? <a href="/auth/register">Inscrivez-vous</a>
                </p>
            </div>
        </article>

        <aside>
            <img class="book-cover" src="/img/auth.png" alt="">
        </aside>
    </section>
</main>

