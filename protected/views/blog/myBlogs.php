<?php
// protected/views/blog/myBlogs.php
$this->breadcrumbs = array(
    'My Blogs',
);

$this->menu = array(
    array('label' => 'Create New Blog', 'url' => array('create')),
    array('label' => 'All Blogs', 'url' => array('index')),
);

?>

<h1>My Blog Posts</h1>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'title',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->title), array("view", "id" => $data->id))',
        ),
        array(
            'name' => 'status',
            'value' => '$data->getStatusLabel()["label"]',
            'htmlOptions' => array('style' => 'width: 100px;'),
        ),
        array(
            'name' => 'createdAt',
            'value' => 'date("M d, Y", strtotime($data->createdAt))',
            'htmlOptions' => array('style' => 'width: 120px;'),
        ),
        array(
            'name' => 'publishedAt',
            'value' => '$data->publishedAt ? date("M d, Y", strtotime($data->publishedAt)) : "Not published"',
            'htmlOptions' => array('style' => 'width: 120px;'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view} {update}',
            'buttons' => array(
                'view' => array(
                    'label' => 'View',
                    'imageUrl' => false,
                    'options' => array('class' => 'btn btn-xs btn-default'),
                ),
                'update' => array(
                    'label' => 'Edit',
                    'imageUrl' => false,
                    'options' => array('class' => 'btn btn-xs btn-primary'),
                ),
            ),
        ),
    ),
    'itemsCssClass' => 'table table-striped table-bordered',
    'pager' => array(
        'class' => 'CLinkPager',
        'header' => '',
        'firstPageLabel' => '&lt;&lt;',
        'prevPageLabel' => '&lt;',
        'nextPageLabel' => '&gt;',
        'lastPageLabel' => '&gt;&gt;',
    ),
)); ?>