<main class="our-books">
    <section class="books-list">
        <section class="list-of-books">
            <div class="page-header">
                <h1>
                    Nos livres à l’échange
                </h1>

                <form
                    action="/our-books"
                    method="GET"
                >
                    <label for="search">
                        <figure>
                            <svg width="100%" height="100%" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 16L16.3536 15.6464L16 16ZM13 13L12.6464 12.6464L12.2929 13L12.6464 13.3536L13 13ZM16.7071 16L16.3536 15.6464L16.3536 15.6464L16.7071 16ZM16.7071 15.2929L16.3536 15.6464L16.7071 15.2929ZM13.7071 12.2929L14.0607 11.9393L13.7071 11.5858L13.3536 11.9393L13.7071 12.2929ZM14.5 8C14.5 11.5899 11.5899 14.5 8 14.5V15.5C12.1421 15.5 15.5 12.1421 15.5 8H14.5ZM8 1.5C11.5899 1.5 14.5 4.41015 14.5 8H15.5C15.5 3.85786 12.1421 0.5 8 0.5V1.5ZM1.5 8C1.5 4.41015 4.41015 1.5 8 1.5V0.5C3.85786 0.5 0.5 3.85786 0.5 8H1.5ZM8 14.5C4.41015 14.5 1.5 11.5899 1.5 8H0.5C0.5 12.1421 3.85786 15.5 8 15.5V14.5ZM16.3536 15.6464L13.3536 12.6464L12.6464 13.3536L15.6464 16.3536L16.3536 15.6464ZM16.3536 15.6464L16.3536 15.6464L15.6464 16.3536C16.037 16.7441 16.6701 16.7441 17.0607 16.3536L16.3536 15.6464ZM16.3536 15.6464L16.3536 15.6464L17.0607 16.3536C17.4512 15.963 17.4512 15.3299 17.0607 14.9393L16.3536 15.6464ZM13.3536 12.6464L16.3536 15.6464L17.0607 14.9393L14.0607 11.9393L13.3536 12.6464ZM13.3536 13.3536L14.0607 12.6464L13.3536 11.9393L12.6464 12.6464L13.3536 13.3536Z" fill="#A6A6A6"/>
                            </svg>
                        </figure>
                    </label>
                    <input
                        id="search"
                        type="text"
                        name="title"
                        placeholder="Rechercher un livre"
                        value="<?= $_GET['title'] ?? '' ?>"
                    >
                </form>
            </div>

            <div class="last-books-wrapper">
                <?php if (count($books) > 0): ?>
                    <ul class="grid-of-last-books">
                        <?php foreach ($books as $book): ?>
                            <li>
                                <a href="/books/show/<?= $book->slug ?>">
                                    <article>
                                        <img src="<?= \App\Helpers\File::get($book->cover, 'books') ?>" alt="Image de livre carré">
                                        <div class="book-info">
                                            <h2><?= htmlspecialchars($book->title) ?></h2>
                                            <h3><?= htmlspecialchars($book->author) ?></h3>
                                            <p>
                                                Vendu par: <?= htmlspecialchars($book->relations['user']->username) ?>
                                            </p>
                                        </div>
                                    </article>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <span>Aucun livre ne correspond à la recherche</span>
                <?php endif; ?>
            </div>
        </section>
    </section>
</main>
