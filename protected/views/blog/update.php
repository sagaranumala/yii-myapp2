<?php
// protected/views/blog/update.php
$this->breadcrumbs = array(
    'Blogs' => array('index'),
    $model->title => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'View Blog', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage Blogs', 'url' => array('index')),
    array('label' => 'My Blogs', 'url' => array('myBlogs')),
);

?>

<h1>Update Blog: <?php echo CHtml::encode($model->title); ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>