<main class="conversations-page">
    <div class="centered">
        <?php require('../app/Views/components/conversations_list.php'); ?>

        <section class="conversation-wrapper">
            <div class="header">
                <div class="user-infos">
                    <img src="<?= \App\Helpers\File::get($user->avatar, \App\Enum\EnumFileCategory::AVATAR->value) ?>" alt=""
                         class="profile-picture image-cover">
                    <span>
                        <?= $user->username ?>
                    </span>
                </div>
            </div>

            <div class="conversations-messages">
            </div>

            <form
                    class="message-sender"
                    action="/messages/store"
                    method="POST"
            >
                <input type="hidden" name="receiver_id" value="<?= $user->id ?>">

                <label>
                    <input type="text" name="content" placeholder="Taper votre message ici" autofocus>
                </label>

                <button class="btn btn-send">Envoyer</button>
            </form>
        </section>
    </div>
</main>