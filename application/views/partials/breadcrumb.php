<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?>
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <?php foreach ($breadcrumbs as $label => $link): ?>
            <?php if (!empty($link)): ?>
                <li class="breadcrumb-item"><a href="<?php echo site_url($link); ?>"><?php echo html_escape($label); ?></a></li>
            <?php else: ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo html_escape($label); ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>
