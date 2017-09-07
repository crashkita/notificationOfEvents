<?php
/**
 * @var string $url
 * @var string $publicationName
 * @var \app\models\User $user
 */
?>
<div>
    <p>Добрый день, <?=$user->username?></p>
    <p>На нашем сайте появилась новая публикация </p>
    <p><a href="<?=$url?>"><?=$publicationName?></a></p>
</div>
