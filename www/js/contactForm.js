// $(document).ready(function() {
//     var isSubmitting = false;

//     // This event listener should catch the form submission
//     $('form[data-form-name="contactForm"]').on('submit', function(event) {
//         event.preventDefault();  // Prevent the default form submission

//         // If the form is already submitting, return early
//         if (isSubmitting) return;

//         // Set the submitting flag to prevent further submissions
//         isSubmitting = true;

//         var $form = $(this);
//         var $loading = $form.find('.loading');  // Loading spinner element
//         var $responseContainer = $form.find('.form-response');  // Message container
//         var $message = $responseContainer.find('.message');  // Message element

//         // Get the time when the submit button was clicked
//         var submitTime = Date.now();

//         // Show the loading spinner
//         $loading.show();

//         // Clear any previous messages
//         $message.text('');
//         $responseContainer.hide();

//         // Disable the submit button to prevent multiple clicks
//         var $submitButton = $form.find('button[type="submit"]');
//         $submitButton.prop('disabled', true);

//         // Perform the AJAX request
//         $.ajax({
//             url: $form.attr('action'),  // Action URL to submit the form to (handled by Nette)
//             method: 'POST',
//             data: $form.serialize() + '&submitTime=' + submitTime, // Pass submitTime to the server
//             success: function(response) {
//                 // Hide loading spinner
//                 $loading.hide();

//                 // Show success message
//                 $message.text(response.successMessage);
//                 $responseContainer.show();

//                 // Optionally, reset the form fields
//                 $form[0].reset();  // Reset form fields after successful submission
//             },
//             error: function() {
//                 // Hide loading spinner
//                 $loading.hide();

//                 // Show error message
//                 $message.text('Something went wrong. Please try again.');
//                 $responseContainer.show();
//             },
//             complete: function() {
//                 // Reset the submitting flag to allow future submissions
//                 isSubmitting = false;

//                 // Re-enable the submit button after completion
//                 $submitButton.prop('disabled', false);
//             }
//         });
//     });
// });
