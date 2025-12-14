<?php
// protected/views/blog/_view.php
$statusLabel = $data->getStatusLabel();
?>
<div class="blog-post">
    <h3>
        <?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id' => $data->id)); ?>
        <small class="status-label <?php echo $statusLabel['class']; ?>">
            <?php echo $statusLabel['label']; ?>
        </small>
    </h3>
    
    <?php if ($data->excerpt): ?>
        <p><?php echo CHtml::encode($data->excerpt); ?></p>
    <?php else: ?>
        <p><?php echo substr(strip_tags($data->content), 0, 200); ?>...</p>
    <?php endif; ?>
    
    <div class="blog-meta">
        <small>
            By: <?php echo CHtml::encode($data->author->username ?? 'Unknown'); ?> |
            Published: <?php echo $data->publishedAt ? date('F j, Y', strtotime($data->publishedAt)) : 'Not published'; ?>
        </small>
    </div>
    
    <?php echo CHtml::link('Read more →', array('view', 'id' => $data->id), array('class' => 'btn btn-link')); ?>
</div>
<hr>