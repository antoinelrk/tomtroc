<main class="conversations-page">
    <div class="centered">
        <?php require('../app/Views/components/conversations_list.php'); ?>

        <section class="conversation-wrapper">
            <div class="header">
                <div class="user-infos">
                    <img src="<?= \App\Helpers\File::get($selectedConversation->relations['receiver']->avatar, \App\Enum\EnumFileCategory::AVATAR->value) ?>" alt=""
                         class="profile-picture image-cover">
                    <span>
                        <?= $selectedConversation->relations['receiver']->username ?>
                    </span>
                </div>
            </div>

            <div class="conversations-messages">
                <ul class="messages-list">
                    <?php foreach ($selectedConversation->relations['messages'] as $key => $message): ?>
                        <li class="message <?= \App\Core\Auth\Auth::user()->id === $message?->relations['sender']->id ? 'me' : '' ?>">
                            <!-- Si le message précédent contient le même user tu mets ce bloc -->
                            <?php if ($key === 0 || ($key > 0 && $selectedConversation->relations['messages'][$key - 1]->receiver_id === $message->relations['sender']->id)): ?>
                                <div class="metadata">
                                    <img src="<?= \App\Helpers\File::get($message->relations['sender']->avatar, \App\Enum\EnumFileCategory::AVATAR->value) ?>" alt=""
                                         class="mini-profile-picture image-cover">
                                    <span class="date"><?= \App\Helpers\Diamond::diffForHumans($message->created_at, true) ?></span>
                                </div>

                                <div class="message-wrapper">
                                    <p class="message-content">
                                        <?= $message->content ?>
                                    </p>

                                    <span class="message-date">
                                        <?= \App\Helpers\Diamond::format($message->created_at, 'd/m/Y à h:i') ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <div class="message-wrapper collapsed">
                                    <p class="message-content">
                                        <?= $message->content ?>
                                    </p>

                                    <span class="message-date">
                                        <?= \App\Helpers\Diamond::format($message->created_at, 'd/m/Y à h:i') ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form
                class="message-sender"
                action="/messages/store"
                method="POST"
            >
                <input type="hidden" name="conversation_id" value="<?= $selectedConversation->id ?>">
                <input type="hidden" name="uuid" value="<?= $selectedConversation->uuid ?>">
                <input type="hidden" name="receiver_id" value="<?= $selectedConversation->relations['receiver']->id ?>">

                <label>
                    <input type="text" name="content" placeholder="Taper votre message ici" autofocus>
                </label>

                <button class="btn btn-send">Envoyer</button>
            </form>
        </section>
    </div>
</main>
