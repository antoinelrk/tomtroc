<main class="conversations-page">
    <div class="centered">
        <section class="conversations-list">
            <div class="section-title">
                <h1>
                    Messagerie
                </h1>
            </div>
            <ul>
                <?php foreach ($conversations as $conversation): ?>
                    <li>
                        <a href="/conversations/<?= $conversation->uuid ?>">
                            <img src="<?= $conversation->relations[0]['user']->avatar ?>" alt=""
                                 class="profile-picture">

                            <div class="user-conversation-list-infos">
                                <div class="top-user-infos">
                                    <span>
                                        <?= $conversation->relations[0]['user']->display_name ?>
                                    </span>
                                    <span>
                                        <?= \App\Helpers\Diamond::diffForHumans($conversation->updated_at, true) ?>
                                    </span>
                                </div>
                                <p>
                                    <?= \App\Helpers\Str::trunc(end($conversation->relations[0]['messages'])->content, 20) ?>
                                </p>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="conversation-wrapper">
            <div class="header">
                <div class="user-infos">
                    <img src="<?= $currentConversation[0]->relations[0]['user']->avatar ?>" alt=""
                         class="profile-picture">
                    <span>
                        <?= $currentConversation[0]->relations[0]['user']->display_name ?>
                    </span>
                </div>
            </div>

            <div class="conversations-messages">
                <ul class="messages-list">
                    <?php foreach ($currentConversation[0]->relations[0]['messages'] as $key => $message): ?>
                        <li class="message <?= \App\Core\Auth\Auth::user()->id === $message->relations[0]['user']->id ? 'me' : '' ?>">
                            <!-- Si le message précédent contient le même user tu mets pas ce bloc -->
                            <?php if ($key === 0 || ($key > 0 && $currentConversation[0]->relations[0]['messages'][$key - 1]->sender_id === $message->user_id)): ?>
                                <div class="metadata">
                                    <img src="<?= $message->relations[0]['user']->avatar ?>" alt=""
                                         class="mini-profile-picture">
                                    <span class="date"><?= \App\Helpers\Diamond::diffForHumans($message->created_at, true) ?></span>
                                </div>
                            <?php endif; ?>

                            <p class="message-content">
                                <?= $message->content ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form
                    class="message-sender"
                    action="/messages"
                    method="POST"
            >
                <input type="hidden" name="conversation_id" value="<?= $currentConversation[0]->id ?>">
                <input type="hidden" name="uuid" value="<?= $currentConversation[0]->uuid ?>">

                <label>
                    <input type="text" name="content" placeholder="Taper votre message ici">
                </label>

                <button class="btn btn-send">Envoyer</button>
            </form>
        </section>
    </div>
</main>
