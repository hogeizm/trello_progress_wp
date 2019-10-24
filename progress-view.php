<div class="progress-boards">
    <?php foreach($boards as $board): ?>
    <div class="progress-board-contents">
        <div class="progress-board-info">
            <h2 class="progress-board-name"><?php echo($board->name); ?></h2>
        <?php if($board->desc): ?>
            <p class="progress-board-desc"><?php echo str_replace("\n", '<br>', $board->desc); ?></p>
        <?php endif; ?>
        <?php if($board->isPublic): ?>
            <p class="progress-board-url">タスク詳細 &gt;&gt; <a href="<?php echo($board->shortUrl); ?>" target="hogeizm_trello"><?php echo($board->shortUrl); ?></a></p>
        <?php endif; ?>
        </div>
        <table class="progress-table">
            <thead>
            <tr class="progress-table-header-tr">
                <th class="progress-table-th-list">リスト</th>
                <th class="progress-table-th-task">タスク</th>
                <th class="progress-table-th-progress">進捗</th>
                <th class="progress-table-th-update">更新日</th>
            </tr>
            </thead>
            <tbody>
        <?php $index = 0; $loopParentLast = count($board->lists); ?>
        <?php foreach($board->lists as $list): ?>
            <?php
                $cards = $list->cards;
                $isLast = $index == $loopParentLast-1;
                $index2 = 0;
            ?>
            <?php if(count($cards) > 0): ?>
                <?php foreach($cards as $card): ?>
                <tr class="progress-table-tr">
                    <?php if($index2 == 0): ?>
                    <td class="progress-table-td-first<?php echo($isLast ? ' last-heading' : ''); ?>" rowspan="<?php echo(count($list->cards)); ?>"><?php echo($list->name); ?></td>
                    <?php endif; ?>
                    <td class="progress-table-td-task"><?php echo($card->name); ?></td>
                    <td class="progress-table-td-progress">
                    <?php if ($card->checkItems > 0): ?>
                        <div class="items"><?php echo($card->checkItemsChecked); ?>/<?php echo($card->checkItems); ?></div><div class="percentage">(<?php echo(round($card->checkItemsChecked*100/$card->checkItems, 1)); ?>%)</div>
                    <?php else: ?>
                        <div class="items">--/--</div><div class="percentage">(---%)</div>
                    <?php endif; ?>
                    </td>
                    <td class="progress-table-td-update"><?php echo(substr($card->dateLastActivity, 0, 10)); ?></td>
                </tr>
                <?php $index2++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="progress-table-tr">
                    <td class="progress-table-td-first<?php echo($isLast ? ' last-heading' : ''); ?>"><?php echo($list->name); ?></td>
                    <td colspan="3">-</td>
                </tr>
            <?php endif; ?>
            <?php $index++ ?>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>
    <p style="font-size:0.75em; text-align:right;">These tables made by <a href="https://github.com/hogeizm/trello_progress_wp" target="trello_progress_wp">Trello Progress WP</a>.</p>
</div>