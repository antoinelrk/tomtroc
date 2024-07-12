<main class="books-edit">
    <section class="red-line">
        <a href="/our-books">Nos livres</a>
        >
        <a href="/books/<?= $book->slug ?>"><?= $book->title ?></a>
    </section>

    <section class="book">
        <aside>
            <img class="book-cover" src="<?= $book->cover ?>" alt="">
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
                    <img src="https://placehold.co/400" alt="">
                    <?= $book->relations[0]['user']->display_name ?>
                </div>
            </div>

            <a class="send-message" href="/new-message/<?= $book->relations[0]['user']->id ?>">Envoyer un message</a>
        </article>
    </section>
</main>
