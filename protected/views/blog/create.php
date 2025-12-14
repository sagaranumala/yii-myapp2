<?php
// protected/views/blog/create.php
$this->breadcrumbs = array(
    'Blogs' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'Manage Blogs', 'url' => array('index')),
    array('label' => 'My Blogs', 'url' => array('myBlogs')),
);

?>
<h1>Create Blog Post</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>