// assets/js/main.js
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
    
    // Comment form validation
    const commentForm = document.querySelector('.comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            const content = this.querySelector('textarea').value.trim();
            if (content.length < 10) {
                e.preventDefault();
                alert('Comment must be at least 10 characters long');
            }
        });
    }
    
    // Image preview for post creation/edit
    const imageInput = document.querySelector('input[type="file"]');
    const imagePreview = document.querySelector('.image-preview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Auto-save draft posts
    const postForm = document.querySelector('.post-form');
    if (postForm) {
        let timeoutId;
        const autoSave = () => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                const formData = new FormData(postForm);
                formData.append('action', 'autosave');
                
                fetch('/admin/posts/autosave.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Draft saved');
                    }
                })
                .catch(error => console.error('Error:', error));
            }, 3000);
        };
        
        postForm.addEventListener('input', autoSave);
    }
});
