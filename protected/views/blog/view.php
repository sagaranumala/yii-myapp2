<!-- protected/views/blog/view.php -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item">
                <a href="<?php echo $this->createUrl('blog/index'); ?>" class="text-decoration-none text-primary fw-medium">
                    <i class="fas fa-home me-2"></i>Home
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo $this->createUrl('blog/index'); ?>" class="text-decoration-none text-primary fw-medium">
                    <i class="fas fa-newspaper me-2"></i>Blogs
                </a>
            </li>
            <li class="breadcrumb-item active fw-bold text-dark">
                <i class="fas fa-file-alt me-2"></i><?php echo CHtml::encode($model->title); ?>
            </li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="bg-white rounded-4 shadow-lg overflow-hidden">
                <!-- Featured Image -->
                <?php if ($model->featuredImage): ?>
                <div class="featured-image position-relative">
                    <img src="<?php echo CHtml::encode($model->featuredImage); ?>" 
                         class="img-fluid w-100" 
                         alt="<?php echo CHtml::encode($model->title); ?>"
                         style="height: 450px; object-fit: cover;">
                    <div class="position-absolute top-3 start-3">
                        <span class="badge bg-success bg-opacity-90 px-3 py-2">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Article Content -->
                <div class="p-4 p-md-5">
                    <!-- Title -->
                    <h1 class="display-5 fw-bold mb-4 text-gradient">
                        <?php echo CHtml::encode($model->title); ?>
                    </h1>

                    <!-- Author & Meta -->
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary bg-gradient text-white d-flex align-items-center justify-content-center">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-medium">Author ID: <?php echo CHtml::encode($model->userId); ?></div>
                                <div class="text-muted small">
                                    <i class="fas fa-calendar me-1"></i> 
                                    <?php echo date('F j, Y', strtotime($model->createdAt)); ?>
                                    <?php if ($model->status == 'published' && $model->publishedAt): ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-globe me-1"></i> 
                                    Published: <?php echo date('F j, Y', strtotime($model->publishedAt)); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-<?php 
                                echo $model->status == 'published' ? 'success' : 
                                     ($model->status == 'draft' ? 'secondary' : 'warning'); 
                            ?> px-3 py-2">
                                <i class="fas fa-<?php 
                                    echo $model->status == 'published' ? 'check-circle' : 
                                         ($model->status == 'draft' ? 'edit' : 'clock'); 
                                ?> me-1"></i>
                                <?php echo ucfirst($model->status); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <?php if ($model->excerpt): ?>
                    <div class="excerpt-card bg-gradient-info rounded-3 p-4 mb-5 border-start border-4 border-info">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-quote-left fa-2x text-info opacity-75"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="lead fst-italic mb-0">
                                    <?php echo nl2br(CHtml::encode($model->excerpt)); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Content -->
                    <div class="blog-content mb-5">
                        <div class="content-body">
                            <?php echo nl2br(CHtml::encode($model->content)); ?>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-top pt-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <div class="d-flex gap-2">
                                <a href="<?php echo $this->createUrl('blog/index'); ?>" 
                                   class="btn btn-outline-primary btn-lg rounded-pill px-4">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Blogs
                                </a>
                                <button class="btn btn-outline-secondary btn-lg rounded-pill px-4" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>Print
                                </button>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <?php if ($model->canEdit()): ?>
                                <a href="<?php echo $this->createUrl('blog/update', array('id' => $model->id)); ?>" 
                                   class="btn btn-primary btn-lg rounded-pill px-4">
                                    <i class="fas fa-edit me-2"></i>Edit Post
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($model->canDelete()): ?>
                                <a href="<?php echo $this->createUrl('blog/delete', array('id' => $model->id)); ?>" 
                                   class="btn btn-danger btn-lg rounded-pill px-4" 
                                   onclick="return confirm('Are you sure you want to delete this blog? This action cannot be undone.')">
                                    <i class="fas fa-trash-alt me-2"></i>Delete Post
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Blog Info Card -->
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Blog Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 border-0 d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-calendar-plus fa-lg text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium mb-1">Created</div>
                                <div class="text-muted"><?php echo date('F j, Y, g:i a', strtotime($model->createdAt)); ?></div>
                            </div>
                        </div>
                        
                        <div class="list-group-item px-0 border-0 d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-calendar-check fa-lg text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium mb-1">Last Updated</div>
                                <div class="text-muted"><?php echo date('F j, Y, g:i a', strtotime($model->updatedAt)); ?></div>
                            </div>
                        </div>
                        
                        <?php if ($model->status == 'published' && $model->publishedAt): ?>
                        <div class="list-group-item px-0 border-0 d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-globe fa-lg text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium mb-1">Published</div>
                                <div class="text-muted"><?php echo date('F j, Y, g:i a', strtotime($model->publishedAt)); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="list-group-item px-0 border-0 d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-user fa-lg text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium mb-1">Author ID</div>
                                <div class="text-muted"><?php echo CHtml::encode($model->userId); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-warning text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-share-alt me-2"></i>Share This Blog
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button class="btn btn-outline-primary btn-lg flex-fill rounded-pill">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </button>
                        <button class="btn btn-outline-info btn-lg flex-fill rounded-pill">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </button>
                    </div>
                    <div>
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   value="<?php echo Yii::app()->request->hostInfo . Yii::app()->request->requestUri; ?>" 
                                   readonly>
                            <button class="btn btn-outline-primary" onclick="copyToClipboard(this)">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styles */
:root {
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-info: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
}

.text-gradient {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.bg-gradient-primary {
    background: var(--gradient-primary) !important;
}

.bg-gradient-info {
    background: var(--gradient-info) !important;
}

.bg-gradient-warning {
    background: var(--gradient-warning) !important;
}

.avatar-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.avatar-circle:hover {
    transform: scale(1.05);
}

.excerpt-card {
    background: linear-gradient(to right, #f8f9fa, #e3f2fd);
}

.content-body {
    line-height: 1.8;
    font-size: 1.125rem;
    color: #374151;
}

.content-body p {
    margin-bottom: 1.5rem;
}

.blog-content {
    font-family: 'Poppins', sans-serif;
}

.featured-image img {
    transition: transform 0.5s ease;
}

.featured-image:hover img {
    transform: scale(1.02);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.list-group-item {
    padding-left: 0;
    padding-right: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .display-5 {
        font-size: 2rem;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .featured-image img {
        height: 300px;
    }
}
</style>

<script>
function copyToClipboard(button) {
    const input = button.parentElement.querySelector('input');
    input.select();
    input.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        
        // Visual feedback
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
        }, 2000);
    } catch (err) {
        console.error('Failed to copy:', err);
    }
}

// Add social sharing functionality
document.addEventListener('DOMContentLoaded', function() {
    const shareButtons = document.querySelectorAll('.btn-outline-primary, .btn-outline-info');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function() {
            const text = "Check out this blog post: " + document.title;
            const url = window.location.href;
            
            if (this.querySelector('.fa-facebook-f')) {
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
            } else if (this.querySelector('.fa-twitter')) {
                window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`, '_blank');
            }
        });
    });
});
</script>