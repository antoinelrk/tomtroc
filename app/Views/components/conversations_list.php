<section class="conversations-list">
    <div class="section-title">
        <h1>
            Messagerie
        </h1>
    </div>
    <?php if(empty($conversations)): ?>
        <span>Vous n'avez aucun message</span>
    <?php else: ?>
        <ul>
            <?php if (isset($user)): ?>
                <li>
                    <div class="conversation-selector selected">
                        <img
                            src="<?= \App\Helpers\File::get($user->avatar,'avatars') ?>"
                            alt=""
                            class="profile-picture"
                        >
                        <div class="user-conversation-list-infos">
                            <div class="top-user-infos">
                                <span>
                                    <?= $user->username ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endif; ?>
            <?php foreach ($conversations as $conversation): ?>
                <li>
                    <a class="conversation-selector" href="/conversations/show/<?= $conversation->uuid ?>">
                        <img
                            src="<?= \App\Helpers\File::get($conversation->relations['receiver']->avatar,'avatars') ?>"
                            alt=""
                            class="profile-picture"
                        >

                        <div class="user-conversation-list-infos">
                            <div class="top-user-infos">
                                <span>
                                    <?= $conversation->relations['receiver']->username ?>
                                </span>
                                <span>
                                    <?= \App\Helpers\Diamond::diffForHumans($conversation->updated_at, true) ?>
                                </span>
                            </div>
                            <p>
                                <?= \App\Helpers\Str::trunc(end($conversation->relations['messages'])->content, 20) ?>
                            </p>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>