const videoElement = document.getElementById('video');

// Function to start the video stream
async function startVideo() {
    videoElement.srcObject = await navigator.mediaDevices.getUserMedia({ video: true });
}

// Function to load the facial recognition model
async function loadModel() {
    const model = await blazeface.load();
    return model;
}

// Function to detect faces and send face data to the server
async function detectFace(model) {
    const predictions = await model.estimateFaces(videoElement, false);
    if (predictions.length > 0) {
        // Extract facial data (landmarks)
        const faceData = predictions[0].landmarks;
        console.log('Face detected:', faceData);

        // Send facial data to the server for verification
        verifyFace(faceData);
    }
}

// Function to send face data to the PHP server for verification
function verifyFace(faceData) {
    fetch('verifyFace.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ faceData: faceData })
    })
        .then(response => {
            // Check if the response status is OK and is JSON
            if (!response.ok) {
                throw new Error(`Network response was not OK: ${response.statusText}`);
            }
            return response.text(); // Get the response as text to check for HTML errors
        })
        .then(text => {
            try {
                const data = JSON.parse(text); // Attempt to parse JSON

                if (data.success) {
                    alert(data.message || 'Attendance logged successfully!');
                    window.location.href = 'index.html'; // Redirect to index.html on success
                } else {
                    alert(data.message || 'Face not recognized.');
                }
            } catch (error) {
                console.error('Invalid JSON:', text);
                alert('An error occurred while verifying the face.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while verifying the face.');
        });
}

// Start the video and load the model
(async () => {
    await startVideo();
    const model = await loadModel();

    // Periodically check for faces
    setInterval(() => detectFace(model), 2000);
})();
