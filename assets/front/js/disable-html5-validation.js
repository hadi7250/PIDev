// Completely disable HTML5 validation - server-side only
document.addEventListener('DOMContentLoaded', function() {
    console.log('Disabling HTML5 validation...');
    
    // Disable HTML5 validation completely
    const disableHTML5Validation = function() {
        // Remove HTML5 validation from all forms
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            form.setAttribute('novalidate', '');
            form.setAttribute('autocomplete', 'off');
            
            // Override form submit to prevent HTML5 validation
            form.addEventListener('submit', function(e) {
                // Force submission without HTML5 validation
                console.log('Form submitted - server-side validation only');
                return true;
            });
        });
        
        // Remove all HTML5 validation attributes from inputs
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(function(input) {
            // Remove all HTML5 validation attributes
            input.removeAttribute('required');
            input.removeAttribute('pattern');
            input.removeAttribute('minlength');
            input.removeAttribute('maxlength');
            input.removeAttribute('min');
            input.removeAttribute('max');
            input.removeAttribute('step');
            input.setAttribute('autocomplete', 'off');
            input.setAttribute('novalidate', '');
            
            // Remove HTML5 validation event listeners
            input.addEventListener('invalid', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
            
            // Override input validation
            input.addEventListener('input', function(e) {
                e.target.setCustomValidity('');
            });
        });
        
        // Override form validation
        const overrideValidation = function() {
            const originalCheckValidity = HTMLFormElement.prototype.checkValidity;
            HTMLFormElement.prototype.checkValidity = function() {
                return true; // Always return true to bypass HTML5 validation
            };
            
            const originalReportValidity = HTMLFormElement.prototype.reportValidity;
            HTMLFormElement.prototype.reportValidity = function() {
                return true; // Always return true to bypass HTML5 validation
            };
        };
        
        overrideValidation();
    };
    
    // Run immediately and again after DOM is fully loaded
    disableHTML5Validation();
    setTimeout(disableHTML5Validation, 100);
    setTimeout(disableHTML5Validation, 500);
    
    // Add CSS to remove HTML5 validation styling
    const style = document.createElement('style');
    style.textContent = `
        :invalid {
            box-shadow: none !important;
            border-color: #ced4da !important;
            outline: none !important;
        }
        :valid {
            box-shadow: none !important;
            outline: none !important;
        }
        input:focus:invalid, textarea:focus:invalid, select:focus:invalid {
            border-color: #ced4da !important;
            box-shadow: none !important;
        }
    `;
    document.head.appendChild(style);
    
    console.log('HTML5 validation disabled - server-side validation only');
});
