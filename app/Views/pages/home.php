<main class="home-page">
    <section class="home-wrapper">
        <section class="hero">
            <!-- Hero -->
            <article class="hero-content">
                <h1 class="title-3">
                    Rejoignez nos<br> lecteurs passionnés
                </h1>

                <p class="hero-text">
                    Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
                </p>

                <a class="hero-call-to-action" href="">
                    Découvrir
                </a>
            </article>

            <aside class="hero-image">
                <img src="./img/hero.webp" alt="Un homme au milieu de millier de livre en train de lire">
            </aside>
        </section>
    </section>

    <section class="home-wrapper">
        <section class="centered last-books"> <!-- Grid || Flex -->
            <h1>
                Les derniers livres ajoutés
            </h1>

            <div class="last-books-wrapper">
                <ul class="grid-of-last-books">
                    <?php foreach ($books as $book): ?>
                    <li>
                        <article>
                            <img class="book-cover" src="<?= \App\Helpers\File::get($book->cover, \App\Enum\EnumFileCategory::BOOK->value) ?>" alt="Image de livre carré">
                            <div class="book-info">
                                <h2><?= htmlspecialchars($book->title) ?></h2>
                                <h3><?= htmlspecialchars($book->author) ?></h3>
                                <p>
                                    Vendu par: <?= htmlspecialchars($book->relations['user']->username) ?>
                                </p>
                            </div>
                        </article>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <a class="more-books" href="/our-books">Voir tous les livres</a>
        </section>
    </section>

    <section class="home-wrapper">
        <section class="how-its-work">
            <h1>Comment ça marche ?</h1>
            <p>
                Échanger des livres avec TomTroc c'est simple et<br> amusant ! Suivez ces étapes pour commencer :
            </p>

            <div class="steps-wrapper">
                <ul class="grid-of-steps">
                    <li>
                        Inscrivez-vous gratuitement sur notre plateforme
                    </li>

                    <li>
                        Ajoutez les livres que vous souhaitez échanger à votre profil.
                    </li>

                    <li>
                        Parcourez les livres disponibles chez d'autres membres.
                    </li>

                    <li>
                        Proposez un échange et discutez avec d'autres passionnés de lecture.
                    </li>
                </ul>

            </div>

            <a class="link-revert" href="#">Voir tous les livres</a>
        </section>
    </section>

    <section class="home-wrapper">
        <img src="./img/darwin-vegher-W_ZYCEUapF0-unsplash.webp" alt="" class="separator-banner">
    </section>

    <section class="home-wrapper">
        <section class="our-principles bg-lighten">
            <h1>Nos Valeurs</h1>

            <div class="text-wrapper">
                <p>
                    Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes.
                </p>

                <p>
                    Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé.
                </p>

                <p>
                    Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les étagères.
                </p>
            </div>

            <div class="signature">
                <span>L'équipe TomTroc</span>
                <figure>
                    <svg width="100%" height="100%" viewBox="0 0 122 104" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 96.2224V96.2224C2.29696 95.8216 6.2879 96.4842 7.64535 96.4785C34.2391 96.3656 77.2911 74.6923 96.4064 56.0062C109.127 40.7664 119.928 7.80529 85.8057 2.24352C65.0283 -1.1431 50.1873 26.7966 62.0601 33.1465C66.0177 35.2631 78.258 25.6112 65.0283 12.4034C51.7986 -0.804455 39.7279 0.126873 35.3463 2.24352C15.417 7.74679 2.27208 42.7137 71.8127 87.7558C96.4064 103.685 121 102.996 121 102.996" stroke="#00AC66" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </figure>
            </div>
        </section>
    </section>
</main>
