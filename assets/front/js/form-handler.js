// Simple form handler without HTML5 validation
document.addEventListener('DOMContentLoaded', function() {
    // Find all forms and remove HTML5 validation
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        // Remove HTML5 validation
        form.setAttribute('novalidate', '');
        
        // Add custom submit handler
        form.addEventListener('submit', function(e) {
            // Let the form submit normally - server will handle validation
            console.log('Form submitted - server will validate');
        });
    });
    
    // Remove required attributes from inputs
    const inputs = document.querySelectorAll('input[required], textarea[required], select[required]');
    inputs.forEach(function(input) {
        input.removeAttribute('required');
    });
    
    // Add simple client-side validation feedback (optional)
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            // Clear previous error messages
            const errorMessages = document.querySelectorAll('.field-error');
            errorMessages.forEach(function(error) {
                error.remove();
            });
            
            // Simple validation feedback (optional, not required)
            const form = button.closest('form');
            if (form) {
                const textInputs = form.querySelectorAll('input[type="text"], textarea');
                let hasEmptyRequired = false;
                
                textInputs.forEach(function(input) {
                    if (!input.value.trim()) {
                        // Add visual feedback (optional)
                        input.style.borderColor = '#dc3545';
                        hasEmptyRequired = true;
                    } else {
                        input.style.borderColor = '';
                    }
                });
                
                // Still allow submission - server will handle validation
                if (hasEmptyRequired) {
                    console.log('Some fields are empty, but server will validate');
                }
            }
        });
    });
});
