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
                        <!-- <pre><?php var_dump($conversation); ?></pre> -->
                        <a href="/messages/<?= $conversation['uuid'] ?>">
                            <img src="<?= $conversation['users']['target']['avatar'] ?>" alt="" class="profile-picture">

                            <div class="user-conversation-list-infos">
                                <div class="top-user-infos">
                                    <span>
                                        <?= $conversation['users']['target']['display_name'] ?>
                                    </span>
                                    <span>
                                        <!-- TODO: Formatter la date en fonction de l'offset (Si c'est encore aujourd'hui on mets l'heure, sinon hier) -->
                                        <?= $conversation['updated_at'] ?>
                                    </span>
                                </div>
                                <p>
                                    <!-- TODO: Ne laisser passer qu'un certains nombre de mots et ajouter les ...-->
                                    Lorem ipsum dolor sit amet...
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
                    <img src="<?= $currentConversation['users']['target']['avatar'] ?>" alt=""
                         class="profile-picture">
                    <span>
                        <?= $currentConversation['users']['target']['display_name'] ?>
                    </span>
                </div>
            </div>

            <div class="conversations-messages">
                <ul class="messages-list">
                    <?php foreach ($currentConversation['messages'] as $key => $message): ?>
                        <li class="message <?= \App\Core\Auth\Auth::user()['id'] === $message['user']['id'] ? 'me' : '' ?>">
                            <!-- Si le message précédent contient le même user tu mets pas ce bloc -->
                            <?php if ($currentConversation['messages'][$key]['user']['id'] === $message['user']['id']): ?>
                                <div class="metadata">
                                    <img src="<?= $message['user']['avatar'] ?>" alt=""
                                         class="mini-profile-picture">
                                    <span class="date">21.08 15:48</span>
                                </div>
                            <?php endif; ?>
                            <p class="message-content">
                                <?= $message['content'] ?>
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
                <label>
                    <input type="text" placeholder="Taper votre message ici">
                </label>

                <button class="btn btn-send">Envoyer</button>
            </form>
        </section>
    </div>
</main>