
$(document).ready(function() {
    $('form[data-form-name="contactForm"]').on('submit', function(event) {
        event.preventDefault();  // Prevent default form submission

        var $form = $(this);
        var $loading = $form.find('.loading');  // Find the loading spinner
        var $responseContainer = $form.find('.form-response');
        var $message = $responseContainer.find('.message');

        // Show loading spinner when form is being submitted
        $loading.show();

        // Clear any previous messages
        $message.text('');
        $responseContainer.hide();

        // Perform the AJAX request
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function(response) {
                // Hide loading spinner
                $loading.hide();

                // Show the success message
                $message.text(response.successMessage);
                $responseContainer.show();

                // Optionally clear the form fields if you don't want to hide the form
                $form[0].reset();  // Reset form fields
            },
            error: function() {
                // Hide loading spinner
                $loading.hide();

                // Show error message
                $message.text('Something went wrong. Please try again.');
                $responseContainer.show();
            }
        });
    });
});