<main class="books-show">
    <section class="red-line">
        <div class="wrapper">
            <a href="/our-books">Nos livres</a>
            >
            <a href="/books/show/<?= $book->slug ?>"><?= htmlspecialchars($book->title) ?></a>
        </div>
    </section>

    <section class="book">
        <aside>
            <img class="book-cover" src="<?= \App\Helpers\File::get($book->cover, 'books') ?>" alt="">
        </aside>

        <article>
            <div class="book-head">
                <h1><?= htmlspecialchars($book->title) ?></h1>
                <span class="book-author">par <?= htmlspecialchars($book->author) ?></span>
            </div>

            <div class="book-content">
                <h2>Description</h2>
                <p><?= htmlspecialchars($book->description) ?></p>
            </div>

            <div class="book-footer">
                <h2>Propriétaire</h2>
                <div class="book-owner">
                    <img src="<?= \App\Helpers\File::get($book->relations['user']->avatar, 'avatars') ?>" alt="">
                    <?= htmlspecialchars($book->relations['user']->username) ?>
                </div>
            </div>
            <?php if ($book->relations['user']->id !== \App\Core\Auth\Auth::user()->id): ?>
                <a class="send-message" href="/conversations/create/<?= $book->relations['user']->id ?>">Envoyer un message</a>
            <?php endif; ?>
        </article>
    </section>
</main>