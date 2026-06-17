<?php
$themeColor = $themeColor ?? 'purple';
if ($themeColor === 'white') {
$activeBtn = 'background-color: #111827; border-color: #111827; color: #ffffff; font-weight: 600;';}
elseif ($themeColor === 'blue') { 
    $activeBtn = 'background-color: #2563eb; border-color: #2563eb; color: #fff; font-weight: 600; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);'; 
}
elseif ($themeColor === 'sky') { 
    $activeBtn = 'background-color: #0ea5e9; border-color: #0ea5e9; color: #fff;'; 
}
elseif ($themeColor === 'red' || $themeColor === 'rose') { 
    $activeBtn = 'background-color: #f43f5e; border-color: #f43f5e; color: #fff;'; 
}
elseif ($themeColor === 'pink') { 
    $activeBtn = 'background-color: #db2777; border-color: #db2777; color: #fff;'; 
}
elseif ($themeColor === 'amber' || $themeColor === 'yellow') { 
    $activeBtn = 'background-color: #d97706; border-color: #d97706; color: #fff;'; 
}
elseif ($themeColor === 'green' || $themeColor === 'emerald') { 
    $activeBtn = 'background-color: #059669; border-color: #059669; color: #fff;'; 
}
else { 
    $activeBtn = 'background-color: #a855f7; border-color: #a855f7; color: #fff;'; 
}

?>
<div id="paginationBlock" class="d-flex justify-content-center align-items-center gap-2 p-4 border-top" style="border-color: #f3f4f6 !important;">
    <?php if (isset($currentPage) && $currentPage > 1): ?>
        <a href="?page=<?= $currentPage - 1 ?>" class="page-btn-privia">&larr;</a>
    <?php endif; ?>

    <?php if (isset($totalPages)): ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="page-btn-privia" style="<?= $i == $currentPage ? $activeBtn : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    <?php endif; ?>

    <?php if (isset($currentPage) && isset($totalPages) && $currentPage < $totalPages): ?>
        <a href="?page=<?= $currentPage + 1 ?>" class="page-btn-privia">&rarr;</a>
    <?php endif; ?>
</div>