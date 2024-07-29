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
                    <img
                        src="<?= \App\Helpers\File::get($conversation->relations['messages'][0]->relations['receiver']->avatar,'avatars') ?>"
                        alt=""
                        class="profile-picture"
                    >

                    <div class="user-conversation-list-infos">
                        <div class="top-user-infos">
                                    <span>
                                        <?= $conversation->relations['messages'][0]->relations['receiver']->username ?>
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
</section>