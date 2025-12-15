<!-- protected/views/blog/index.php -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 fw-bold text-primary">
                            <i class="fas fa-newspaper me-2"></i>All Blog Posts
                        </h1>
                        <p class="lead text-muted mb-0">Browse all blog posts from our community</p>
                    </div>
                    <div>
                        <a href="<?php echo $this->createUrl('blog/create'); ?>" class="btn btn-success btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Write New Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Stats -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <a href="<?php echo $this->createUrl('blog/index'); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-globe me-2"></i>Published Only
                        </a>
                        <a href="<?php echo $this->createUrl('blog/myBlogs'); ?>" class="btn btn-outline-info">
                            <i class="fas fa-user me-2"></i>My Blogs
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-2"></i>Filter by Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">All Statuses</a></li>
                                <li><a class="dropdown-item" href="#"><span class="badge bg-success me-2">●</span>Published</a></li>
                                <li><a class="dropdown-item" href="#"><span class="badge bg-secondary me-2">●</span>Draft</a></li>
                                <li><a class="dropdown-item" href="#"><span class="badge bg-warning me-2">●</span>Archived</a></li>
                            </ul>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-layer-group me-1"></i>
                                Total: <?php echo $dataProvider->totalItemCount; ?> blogs
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search blogs...">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blogs Grid -->
    <div class="row">
        <?php if (count($dataProvider->getData()) > 0): ?>
            <?php foreach ($dataProvider->getData() as $blog): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all duration-300">
                    <!-- Blog Image -->
                    <?php if ($blog->featuredImage): ?>
                    <div class="card-img-top position-relative" style="height: 180px; overflow: hidden;">
                        <img src="<?php echo CHtml::encode($blog->featuredImage); ?>" 
                             class="w-100 h-100 object-fit-cover" 
                             alt="<?php echo CHtml::encode($blog->title); ?>">
                        <!-- Status Badge -->
                        <div class="position-absolute top-2 end-2">
                            <span class="badge rounded-pill bg-<?php 
                                echo $blog->status == 'published' ? 'success' : 
                                     ($blog->status == 'draft' ? 'secondary' : 'warning'); 
                            ?>">
                                <?php echo ucfirst($blog->status); ?>
                            </span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 180px;">
                        <div class="text-center text-white">
                            <i class="fas fa-newspaper fa-3x mb-2"></i>
                            <p class="mb-0 fw-bold"><?php echo CHtml::encode(substr($blog->title, 0, 20)); ?>...</p>
                        </div>
                        <!-- Status Badge -->
                        <div class="position-absolute top-2 end-2">
                            <span class="badge rounded-pill bg-<?php 
                                echo $blog->status == 'published' ? 'success' : 
                                     ($blog->status == 'draft' ? 'secondary' : 'warning'); 
                            ?>">
                                <?php echo ucfirst($blog->status); ?>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Card Body -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="min-height: 48px;">
                            <a href="<?php echo $this->createUrl('blog/view', array('id' => $blog->id)); ?>" 
                               class="text-dark text-decoration-none stretched-link">
                                <?php echo CHtml::encode($blog->title); ?>
                            </a>
                        </h5>
                        
                        <p class="card-text text-muted small mb-3 line-clamp-3" style="min-height: 60px;">
                            <?php echo CHtml::encode(substr($blog->excerpt ?: $blog->content, 0, 120)); ?>...
                        </p>
                        
                        <!-- Author and Date -->
                        <div class="mt-auto">
                            <div class="d-flex align-items-center justify-content-between">
                                <!-- 
                                Temporarily commented out author section
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Author</small>
                                        <small class="fw-medium"></small>
                                    </div>
                                </div>
                                -->
                                <div class="text-end">
                                    <small class="text-muted d-block">Created</small>
                                    <small class="fw-medium"><?php echo date('M j', strtotime($blog->createdAt)); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer Actions -->
                    <div class="card-footer bg-white border-top-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?php echo $this->createUrl('blog/view', array('id' => $blog->id)); ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            <?php if ($blog->canEdit()): ?>
                            <a href="<?php echo $this->createUrl('blog/update', array('id' => $blog->id)); ?>" 
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <?php endif; ?>
                            <span class="text-muted small">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo date('g:i A', strtotime($blog->createdAt)); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body py-5 text-center">
                    <div class="empty-state">
                        <div class="empty-state-icon bg-light-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                            <i class="fas fa-newspaper fa-3x text-primary"></i>
                        </div>
                        <h3 class="fw-bold mb-3">No Blogs Found</h3>
                        <p class="text-muted mb-4">There are no blog posts to display. Be the first to share your story!</p>
                        <a href="<?php echo $this->createUrl('blog/create'); ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-pen-fancy me-2"></i>Create Your First Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($dataProvider->pagination->pageSize < $dataProvider->totalItemCount): ?>
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Blog pagination">
                <?php $this->widget('CLinkPager', array(
                    'pages' => $dataProvider->pagination,
                    'header' => '',
                    'firstPageLabel' => '<i class="fas fa-angle-double-left"></i> First',
                    'lastPageLabel' => 'Last <i class="fas fa-angle-double-right"></i>',
                    'prevPageLabel' => '<i class="fas fa-angle-left"></i> Previous',
                    'nextPageLabel' => 'Next <i class="fas fa-angle-right"></i>',
                    'htmlOptions' => array('class' => 'pagination justify-content-center'),
                    'selectedPageCssClass' => 'page-item active',
                    'hiddenPageCssClass' => 'page-item disabled',
                    'linkOptions' => array('class' => 'page-link'),
                    'maxButtonCount' => 5,
                )); ?>
            </nav>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Add Bootstrap 5 CSS if not already included -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<style>
/* Custom Styles */
.hover-shadow-lg:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
}
.transition-all {
    transition: all 0.3s ease;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.object-fit-cover {
    object-fit: cover;
}
.avatar-sm {
    width: 32px;
    height: 32px;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.empty-state-icon {
    margin: 0 auto;
}
.card-title a:hover {
    color: #0d6efd !important;
}
.badge.bg-success { background-color: #198754 !important; }
.badge.bg-secondary { background-color: #6c757d !important; }
.badge.bg-warning { background-color: #ffc107 !important; color: #000; }
.badge.bg-light { background-color: #f8f9fa !important; }
</style>

<!-- Bootstrap 5 JS for dropdowns -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>