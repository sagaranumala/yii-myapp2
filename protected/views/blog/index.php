<?php
// protected/views/blog/index.php
$this->breadcrumbs = array(
    'Blogs',
);

$this->menu = array(
    array('label' => 'Create Blog', 'url' => array('create'), 'visible' => !Yii::app()->user->isGuest),
    array('label' => 'My Blogs', 'url' => array('myBlogs'), 'visible' => !Yii::app()->user->isGuest),
);

?>

<h1>Blog Posts</h1>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'template' => '{items} {pager}',
    'pager' => array(
        'header' => '',
        'firstPageLabel' => '&lt;&lt;',
        'prevPageLabel' => '&lt;',
        'nextPageLabel' => '&gt;',
        'lastPageLabel' => '&gt;&gt;',
    ),
)); ?>