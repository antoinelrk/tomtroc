<main class="books-show">
    <section class="red-line">
        <a href="all-books.html">Nos livres</a>
        >
        <a href="single-book.html"><?= $book->title ?></a>
    </section>

    <section class="book">
        <aside>
            <img class="book-cover" src="./assets/images/books/original/frosty-ilze-tfYL1j1jKNo-unsplash.png" alt="">
        </aside>

        <article>
            <div class="book-head">
                <h1><?= $book->title ?></h1>
                <span>par <?= $book->author ?></span>
            </div>

            <div class="book-content">
                <h2>Description</h2>
                <p><?= $book->description ?></p>
            </div>

            <div class="book-footer">
                <h2>Propriétaire</h2>
                <div class="book-owner">
                    <img src="./assets/images/avatars/priscilla-du-preez-aqGIS55Fjg4-unsplash.png" alt="">
                    Alexlecture
                </div>
            </div>
            <!-- TODO: Ce lien doit avoir en paramètres tout ce qu'il faut pour configurer le formulaire d'envoi de message (ID, début de message comme: "Bonjour Alex! ...") -->
            <a class="send-message" href="">Envoyer un message</a>
        </article>
    </section>
</main>
