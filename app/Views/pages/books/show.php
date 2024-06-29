<main class="books-show">
    <section class="red-line">
        <a href="/our-books">Nos livres</a>
        >
        <a href="/books/<?= $book['slug'] ?>"><?= $book['title'] ?></a>
    </section>

    <section class="book">
        <aside>
            <img class="book-cover" src="<?= $book['cover'] ?>" alt="">
        </aside>

        <article>
            <div class="book-head">
                <h1><?= $book['title'] ?></h1>
                <span>par <?= $book['author'] ?></span>
            </div>

            <div class="book-content">
                <h2>Description</h2>
                <p><?= $book['description'] ?></p>
            </div>

            <div class="book-footer">
                <h2>Propriétaire</h2>
                <a class="book-owner" href="/users/<?= $book['username'] ?>">
                    <img src="<?= $book['avatar'] ?>" alt="">
                    <?= $book['display_name'] ?>
                </a>
            </div>
            <!-- TODO: Ce lien doit avoir en paramètres tout ce qu'il faut pour configurer le formulaire d'envoi de message (ID, début de message comme: "Bonjour Alex! ...") -->
            <a class="send-message" href="">Envoyer un message</a>
        </article>
    </section>
</main>