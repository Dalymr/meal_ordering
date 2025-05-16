// FoodFrenzy Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('FoodFrenzy scripts loaded');
    
    // Initialize all Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize all Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(function(popoverTriggerEl) {
        new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Profile image upload
    const profileImageInput = document.getElementById('profile_image');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function() {
            document.getElementById('profile-image-form').submit();
        });
    }
    
    // Delete confirmation modals
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
    
    // Menu image preview
    const mealImageInput = document.querySelector('input[type="file"][name="image"]');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    
    if (mealImageInput && imagePreviewContainer) {
        mealImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    preview.src = e.target.result;
                    imagePreviewContainer.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Mobile menu toggle
    const menuToggle = document.querySelector('.navbar-toggler');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            document.body.classList.toggle('menu-open');
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const autoHideAlerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    autoHideAlerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Form validation using Bootstrap's validation styles
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    // Image preview for file inputs
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const previewId = input.getAttribute('data-preview');
                if (previewId) {
                    const preview = document.getElementById(previewId);
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            }
        });
    });
    
    // Password strength meter
    const passwordInputs = document.querySelectorAll('input[type="password"][data-password-strength]');
    passwordInputs.forEach(input => {
        const strengthMeter = document.getElementById(input.getAttribute('data-password-strength'));
        if (strengthMeter) {
            input.addEventListener('input', function() {
                const value = input.value;
                let strength = 0;
                
                if (value.length >= 8) strength += 1;
                if (value.match(/[a-z]/)) strength += 1;
                if (value.match(/[A-Z]/)) strength += 1;
                if (value.match(/[0-9]/)) strength += 1;
                if (value.match(/[^a-zA-Z0-9]/)) strength += 1;
                
                switch (strength) {
                    case 0:
                    case 1:
                        strengthMeter.className = 'progress-bar bg-danger';
                        strengthMeter.style.width = '20%';
                        strengthMeter.textContent = 'Très faible';
                        break;
                    case 2:
                        strengthMeter.className = 'progress-bar bg-warning';
                        strengthMeter.style.width = '40%';
                        strengthMeter.textContent = 'Faible';
                        break;
                    case 3:
                        strengthMeter.className = 'progress-bar bg-info';
                        strengthMeter.style.width = '60%';
                        strengthMeter.textContent = 'Moyen';
                        break;
                    case 4:
                        strengthMeter.className = 'progress-bar bg-primary';
                        strengthMeter.style.width = '80%';
                        strengthMeter.textContent = 'Fort';
                        break;
                    case 5:
                        strengthMeter.className = 'progress-bar bg-success';
                        strengthMeter.style.width = '100%';
                        strengthMeter.textContent = 'Très fort';
                        break;
                }
            });
        }
    });

    // Fix input group styling for login/registration forms
    const authForms = document.querySelectorAll('.auth-form');
    if (authForms.length) {
        // Fix input group heights and alignment
        const inputGroups = document.querySelectorAll('.auth-form .input-group');
        inputGroups.forEach(group => {
            // Ensure proper height for input group text
            const inputGroupText = group.querySelector('.input-group-text');
            if (inputGroupText) {
                inputGroupText.style.height = '100%';
                inputGroupText.style.display = 'flex';
                inputGroupText.style.alignItems = 'center';
                inputGroupText.style.padding = '0.8rem';
            }
        });

        // Ensure buttons display text properly
        const submitButtons = document.querySelectorAll('.auth-submit-btn');
        submitButtons.forEach(button => {
            button.style.whiteSpace = 'normal';
            button.style.wordWrap = 'break-word';
        });
    }

    // Fix auth form styling issues
    // Ensure text visibility in headers and buttons
    document.querySelectorAll('.auth-form h2, .auth-form .auth-submit-btn').forEach(el => {
        el.style.color = 'inherit';
        el.style.opacity = '1';
    });

    // Fix input group alignment
    document.querySelectorAll('.auth-form .input-group-text').forEach(icon => {
        icon.style.display = 'flex';
        icon.style.alignItems = 'center';
        icon.style.justifyContent = 'center';
        icon.style.height = '100%';
    });
    
    // Ensure form controls have consistent height
    document.querySelectorAll('.auth-form .form-control').forEach(input => {
        input.style.height = '48px';
    });
});

// Ensure perfect alignment of input group icons
document.addEventListener('DOMContentLoaded', function() {
    // Get all input groups in auth forms
    const authInputGroups = document.querySelectorAll('.auth-form .input-group');
    
    authInputGroups.forEach(group => {
        // Get the input and icon container
        const input = group.querySelector('.form-control');
        const iconContainer = group.querySelector('.input-group-text');
        
        if (input && iconContainer) {
            // Get computed height of the input
            const inputHeight = window.getComputedStyle(input).height;
            
            // Set icon container to exactly match input height
            iconContainer.style.height = inputHeight;
            
            // Force flex centering
            iconContainer.style.display = 'flex';
            iconContainer.style.alignItems = 'center';
            iconContainer.style.justifyContent = 'center';
        }
    });
});
