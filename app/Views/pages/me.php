<main class="private-account">
    <h1>Mon compte</h1>
    <form action="/users/update/<?= $user->id ?>" method="post" enctype="multipart/form-data" class="form-hero">
        <?= \App\Core\Http\Csrf::template() ?>
        <section class="form-section left">
            <div class="profile-picture">
                <img src="<?= \App\Helpers\File::get($user->avatar, 'avatars') ?>" alt="">

                <div class="form-group">
                    <label for="avatar" class="edit-image">modifier</label>
                    <input type="file" name="avatar" id="avatar" style="display: none">
                </div>
            </div>

            <div class="separator"></div>

            <div class="flex column items-center user-info">
                <p class="text-medium serif"><?= htmlspecialchars($user->username) ?></p>
                <p class="title-secondary">Membre depuis <?= \App\Helpers\Diamond::diffForHumans($user->created_at) ?></p>
                <p class="secondary-title">Bibliothèque</p>
                <div class="text-with-icon">
                    <figure>
                        <svg width="100%" height="100%" viewBox="0 0 11 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.46556 0.160154L7.2112 0.00251429C6.65202 -0.0365878 6.16701 0.385024 6.12791 0.944207L5.32192 12.4705C5.28281 13.0296 5.70442 13.5147 6.26361 13.5538L8.51796 13.7114C9.07715 13.7505 9.56215 13.3289 9.60125 12.7697L10.4072 1.24345C10.4464 0.684262 10.0247 0.199256 9.46556 0.160154ZM6.84113 0.99408C6.85269 0.828798 6.99605 0.70418 7.16133 0.715737L9.41568 0.873377C9.58096 0.884935 9.70558 1.02829 9.69403 1.19357L8.88803 12.7198C8.87647 12.8851 8.73312 13.0097 8.56783 12.9982L6.31348 12.8405C6.1482 12.829 6.02358 12.6856 6.03514 12.5203L6.84113 0.99408Z" fill="#292929"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.27482 0.0648067H1.01496C0.454414 0.0648067 0 0.519224 0 1.07977V12.6342C0 13.1947 0.454416 13.6491 1.01496 13.6491H3.27482C3.83537 13.6491 4.28979 13.1947 4.28979 12.6342V1.07977C4.28979 0.519221 3.83537 0.0648067 3.27482 0.0648067ZM0.714965 1.07977C0.714965 0.914086 0.849279 0.779771 1.01496 0.779771H3.27482C3.44051 0.779771 3.57482 0.914086 3.57482 1.07977V12.6342C3.57482 12.7999 3.44051 12.9342 3.27482 12.9342H1.01496C0.849279 12.9342 0.714965 12.7999 0.714965 12.6342V1.07977Z" fill="#292929"/>
                        </svg>
                    </figure>
                    <?= \App\Helpers\Str::plurialize($quantity, "livre") ?>
                </div>
            </div>
        </section>

        <section class="form-section right">
            <h2 class="section-title">Vos informations personnelles</h2>

            <div class="form-group">
                <label for="username">Pseudo</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user->username) ?>">
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user->email) ?>">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit">
                Enregistrer
            </button>
        </section>
    </form>

    <div class="books-section">
        <table class="list-of-books">
            <tr class="title">
                <th>Photo</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Description</th>
                <th>Disponibilité</th>
                <th>Action</th>
            </tr>

            <?php foreach ($books as $book) : ?>
                <tr class="line">
                    <td>
                        <img class="book-icon" src="<?= \App\Helpers\File::get($book?->cover, 'books') ?>" alt="">
                    </td>
                    <td><?= htmlspecialchars_decode($book->title) ?></td>
                    <td><?= htmlspecialchars($book->author) ?></td>

                    <td class="text">
                        <p><?= htmlspecialchars($book->description) ?></p>
                    </td>

                    <td>
                        <span class="tag <?= $book->available === 0 ? 'unavailable' : 'available' ?>">
                            <?= $book->available === 0 ? 'Indisponible' : 'Disponible' ?>
                        </span>
                    </td>

                    <td class="action">
                        <a href="/books/edit/<?= $book->slug ?>">Editer</a>

                        <form action="/books/delete/<?= $book->slug ?>" class="delete-book" method="POST">
                            <?= \App\Core\Http\Csrf::template(); ?>

                            <label><input type="hidden" value="<?= $book->slug ?>"/></label>
                            <button class="delete deletable-link">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr class="line no-content">
                <td>
                    <a class="button-create-book" href="/books/create">Ajouter un nouveau livre</a>
                </td>
            </tr>
        </table>
    </div>
</main>
