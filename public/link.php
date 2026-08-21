<?php
$targetFolder = __DIR__.'/../storage/app/public'; // Sesuaikan jika struktur path Hostinger Anda berbeda
$linkFolder = __DIR__.'/storage';
symlink($targetFolder, $linkFolder);
echo 'Symlink process done!';
