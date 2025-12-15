<!-- protected/views/blog/create.php -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full shadow-xl mb-4">
                <i class="fas fa-pen text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-3">Create New Blog</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Share your ideas, stories, and knowledge with the world in a beautiful format
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-edit text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Write Your Blog</h2>
                                <p class="text-blue-100">Fill in the details below to create your masterpiece</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <div class="p-8">
                        <?php $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'blog-form',
                            'enableAjaxValidation' => true,
                        )); ?>

                        <!-- Title -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-blue-500 mr-2"></i>Blog Title *
                            </label>
                            <?php echo $form->textField($model, 'title', array(
                                'class' => 'w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200',
                                'placeholder' => 'Enter a captivating title that grabs attention...'
                            )); ?>
                            <?php echo $form->error($model, 'title', array('class' => 'text-red-500 text-sm mt-1')); ?>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                <span>Make it interesting to attract readers!</span>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-align-left text-green-500 mr-2"></i>Short Summary
                            </label>
                            <?php echo $form->textArea($model, 'excerpt', array(
                                'class' => 'w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200',
                                'rows' => 3,
                                'placeholder' => 'Brief summary of your blog (appears in listings)...'
                            )); ?>
                            <?php echo $form->error($model, 'excerpt', array('class' => 'text-red-500 text-sm mt-1')); ?>
                            <div class="flex justify-between items-center mt-2 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>
                                    <span>Max 500 characters</span>
                                </div>
                                <span id="excerpt-counter" class="text-gray-400">0/500</span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-alt text-purple-500 mr-2"></i>Content *
                            </label>
                            <?php echo $form->textArea($model, 'content', array(
                                'class' => 'w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200',
                                'rows' => 15,
                                'placeholder' => 'Start writing your amazing content here... Let your creativity flow...'
                            )); ?>
                            <?php echo $form->error($model, 'content', array('class' => 'text-red-500 text-sm mt-1')); ?>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <i class="fas fa-edit text-gray-400 mr-2"></i>
                                <span>Write from your heart. You can use basic HTML formatting.</span>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image text-pink-500 mr-2"></i>Featured Image URL
                            </label>
                            <div class="relative">
                                <?php echo $form->textField($model, 'featuredImage', array(
                                    'class' => 'w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200',
                                    'placeholder' => 'https://images.unsplash.com/photo-...'
                                )); ?>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                            </div>
                            <?php echo $form->error($model, 'featuredImage', array('class' => 'text-red-500 text-sm mt-1')); ?>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <i class="fas fa-camera text-gray-400 mr-2"></i>
                                <span>Optional. Add a beautiful cover image for your blog.</span>
                            </div>
                        </div>

                        <!-- Status Selection -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-4">
                                <i class="fas fa-globe-americas text-indigo-500 mr-2"></i>Publication Status
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Draft -->
                                <div class="relative">
                                    <input type="radio" name="Blog[status]" value="draft" 
                                           id="status-draft" 
                                           <?php echo ($model->status == 'draft' || empty($model->status)) ? 'checked' : ''; ?>
                                           class="hidden peer">
                                    <label for="status-draft" 
                                           class="block cursor-pointer p-6 border-2 border-gray-200 rounded-xl hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition duration-200">
                                        <div class="text-center">
                                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-edit text-blue-600 text-2xl"></i>
                                            </div>
                                            <h3 class="font-semibold text-gray-800 mb-1">Draft</h3>
                                            <p class="text-sm text-gray-600">Save privately</p>
                                            <div class="mt-3">
                                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                    Only you can see
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Published -->
                                <div class="relative">
                                    <input type="radio" name="Blog[status]" value="published" 
                                           id="status-published" 
                                           <?php echo $model->status == 'published' ? 'checked' : ''; ?>
                                           class="hidden peer">
                                    <label for="status-published" 
                                           class="block cursor-pointer p-6 border-2 border-gray-200 rounded-xl hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 transition duration-200">
                                        <div class="text-center">
                                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-paper-plane text-green-600 text-2xl"></i>
                                            </div>
                                            <h3 class="font-semibold text-gray-800 mb-1">Publish</h3>
                                            <p class="text-sm text-gray-600">Share with world</p>
                                            <div class="mt-3">
                                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                    Public to everyone
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Archived -->
                                <div class="relative">
                                    <input type="radio" name="Blog[status]" value="archived" 
                                           id="status-archived" 
                                           <?php echo $model->status == 'archived' ? 'checked' : ''; ?>
                                           class="hidden peer">
                                    <label for="status-archived" 
                                           class="block cursor-pointer p-6 border-2 border-gray-200 rounded-xl hover:border-yellow-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition duration-200">
                                        <div class="text-center">
                                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-archive text-yellow-600 text-2xl"></i>
                                            </div>
                                            <h3 class="font-semibold text-gray-800 mb-1">Archive</h3>
                                            <p class="text-sm text-gray-600">Keep for reference</p>
                                            <div class="mt-3">
                                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                                    Archived content
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <?php echo $form->error($model, 'status', array('class' => 'text-red-500 text-sm mt-2')); ?>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                    <span>Your blog will be automatically saved</span>
                                </div>
                                <div class="flex space-x-4">
                                    <a href="<?php echo $this->createUrl('blog/index'); ?>" 
                                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition duration-200">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                    <button type="submit" 
                                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition duration-200 transform hover:-translate-y-0.5">
                                        <i class="fas fa-save mr-2"></i>Save Blog Post
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Tips Card -->
                <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg p-6 border border-blue-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-lightbulb text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Writing Tips</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Write a compelling, curiosity-sparking title</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Start with a strong hook in the first paragraph</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Use short paragraphs and subheadings</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Add images to break up text and add visual interest</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Proofread before publishing</span>
                        </li>
                    </ul>
                </div>

                <!-- Auto Fields Card -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-gray-600 to-gray-800 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-magic text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Auto-generated</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-user-circle text-gray-500 mr-3"></i>
                                <span class="text-gray-700">Author</span>
                            </div>
                            <span class="font-medium text-gray-800">Your account</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-plus text-gray-500 mr-3"></i>
                                <span class="text-gray-700">Created At</span>
                            </div>
                            <span class="font-medium text-gray-800">Auto timestamp</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-history text-gray-500 mr-3"></i>
                                <span class="text-gray-700">Updated At</span>
                            </div>
                            <span class="font-medium text-gray-800">Auto-updates</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-hashtag text-gray-500 mr-3"></i>
                                <span class="text-gray-700">Blog ID</span>
                            </div>
                            <span class="font-medium text-gray-800">Auto-incremented</span>
                        </div>
                    </div>
                </div>

                <!-- Character Count Card -->
                <div class="bg-gradient-to-br from-white to-indigo-50 rounded-2xl shadow-lg p-6 border border-indigo-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Character Count</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Title</span>
                                <span id="title-count" class="text-sm font-medium">0/255</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="title-bar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Excerpt</span>
                                <span id="excerpt-count" class="text-sm font-medium">0/500</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="excerpt-bar" class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Content</span>
                                <span id="content-count" class="text-sm font-medium">0 characters</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="content-bar" class="bg-purple-600 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!--  JavaScript to prevent multiple submissions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('blog-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    let isSubmitting = false;
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                e.stopPropagation();
                alert('Please wait, your blog is being created...');
                return false;
            }
            
            // Disable submit button immediately
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Blog...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            
            isSubmitting = true;
            
            // Allow form to submit normally
            return true;
        });
        
        // Re-enable button if form validation fails
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('invalid', function() {
                // Small delay to ensure validation completes
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Blog Post';
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    isSubmitting = false;
                }, 100);
            });
        });
        
        // Also handle form reset
        form.addEventListener('reset', function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Blog Post';
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            isSubmitting = false;
        });
    }
    
    // Also add onclick handler for extra safety
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            if (this.disabled) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
</div>

<!-- JavaScript for character counting -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counters
    const titleInput = document.querySelector('#Blog_title');
    const excerptInput = document.querySelector('#Blog_excerpt');
    const contentInput = document.querySelector('#Blog_content');
    
    // Title counter
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            const count = this.value.length;
            const max = 255;
            const percentage = Math.min((count / max) * 100, 100);
            
            document.getElementById('title-count').textContent = `${count}/${max}`;
            document.getElementById('title-bar').style.width = `${percentage}%`;
        });
    }
    
    // Excerpt counter
    if (excerptInput) {
        excerptInput.addEventListener('input', function() {
            const count = this.value.length;
            const max = 500;
            const percentage = Math.min((count / max) * 100, 100);
            
            document.getElementById('excerpt-count').textContent = `${count}/${max}`;
            document.getElementById('excerpt-bar').style.width = `${percentage}%`;
        });
    }
    
    // Content counter
    if (contentInput) {
        contentInput.addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('content-count').textContent = `${count} characters`;
            document.getElementById('content-bar').style.width = `${Math.min(count / 1000 * 100, 100)}%`;
        });
    }
    
    // Auto-trigger input events for existing content
    if (titleInput && titleInput.value) titleInput.dispatchEvent(new Event('input'));
    if (excerptInput && excerptInput.value) excerptInput.dispatchEvent(new Event('input'));
    if (contentInput && contentInput.value) contentInput.dispatchEvent(new Event('input'));
});
</script>