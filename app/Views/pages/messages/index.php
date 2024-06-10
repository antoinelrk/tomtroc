<main class="messages-pages">
    <section class="conversations-wrapper">
        <section class="left">
            <ul>
                <?php foreach ($conversations as $conversation): ?>
                    <li>
                        <?= $conversation['id'] ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="right">
            <ul>
                <?php foreach ($conversations[0] as $conversation): ?>
                    <li>
                        <pre><?php var_dump($conversation) ?></pre>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </section>
</main>
