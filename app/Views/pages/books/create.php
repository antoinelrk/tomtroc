<main class="books-create">
    <div class="centered">
        <section class="red-line">
            <a href="/me">Retour</a>
        </section>
        <h1 class="page-title">Modifier les informations</h1>

        <form
            class="page-content"
            action="/books/store"
            method="POST"
            enctype="multipart/form-data"
        >
            <aside>
                <img class="cover-book" src="<?= \App\Helpers\File::get(null, 'books') ?>" alt="Couverture du livre">
                <input type="file" id="cover" name="cover">
                <label for="cover" class="cover-input">modifier</label>
            </aside>

            <div class="left-form-part">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" name="title" id="title"/>
                </div>

                <div class="form-group">
                    <label for="author">Auteur</label>
                    <input type="text" name="author" id="author"/>
                </div>

                <div class="form-group">
                    <label for="description">Commentaire</label>
                    <textarea
                        name="description"
                        id="description"
                        cols="30"
                        rows="10"
                        placeholder="Votre commentaire"
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="available">Disponibilité:</label>
                    <select name="available" id="available">
                        <option value="1" selected>Disponible</option>
                        <option value="0">Indisponible</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit">Valider</button>
                </div>
            </div>
        </form>
    </div>
</main>