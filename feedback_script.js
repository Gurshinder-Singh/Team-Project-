document.addEventListener("DOMContentLoaded", function() {
    console.log("Feedback Page Loaded Successfully!");

    document.getElementById('feedbackForm').addEventListener('submit', function(event) {
        event.preventDefault();

        let userName = document.getElementById('userName').value.trim();
        let rating = document.getElementById('rating').value;
        let comment = document.getElementById('comment').value.trim();

        if (!userName || !rating || !comment) {
            alert("Oops! Make sure all fields are filled before submitting.");
            return;
        }

        let feedbackDiv = document.createElement('div');
        feedbackDiv.classList.add('feedback-entry');
        feedbackDiv.innerHTML = `<strong>${userName}</strong> (Rated: ${rating}/5) <br> ${comment}`;

        document.getElementById('feedbackList').appendChild(feedbackDiv);
        document.getElementById('feedbackForm').reset();
    });
});
