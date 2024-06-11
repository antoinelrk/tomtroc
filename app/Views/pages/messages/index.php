<main class="messages-pages">
    <section class="conversations-wrapper">
        <section class="left">
            <h1>Messagerie</h1>
            <ul>
                <?php foreach ($conversations as $conversation): ?>
                    <li>
                        <pre><?php var_dump($conversation) ?></pre>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="right">
            <ul>
                <?php foreach ($conversations as $conversation): ?>
                    <li>
                        <pre><?php var_dump($conversation) ?></pre>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </section>
</main>
