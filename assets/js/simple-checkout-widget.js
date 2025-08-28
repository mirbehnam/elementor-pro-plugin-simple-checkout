jQuery(document).ready(function($) {
    $('.simple-checkout-widget form').on('submit', function(e) {
        e.preventDefault();
        $(this).addClass('processing');
        
        // Add loading state to button
        const submitButton = $(this).find('.checkout-button');
        submitButton.prop('disabled', true);
        submitButton.text('Processing...');
    });
});
