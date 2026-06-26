const feedbackForm = document.getElementById('feedback-form');
if (feedbackForm) {
    feedbackForm.addEventListener('submit', function(event) {
        event.preventDefault();
        alert('Feedback submitted. Thank you!');
    });
}
