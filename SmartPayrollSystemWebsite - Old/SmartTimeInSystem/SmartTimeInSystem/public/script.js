const videoElement = document.getElementById('video');

// Initialize MediaPipe Hands
const hands = new Hands({
    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
});

// Set options for hand detection
hands.setOptions({
    maxNumHands: 1,
    modelComplexity: 1,
    minDetectionConfidence: 0.5,
    minTrackingConfidence: 0.5
});

// Set up the camera
const camera = new Camera(videoElement, {
    onFrame: async () => {
        await hands.send({ image: videoElement });
    },
    width: 640,
    height: 480
});

// Start the camera and display it on the video element
(async () => {
    videoElement.srcObject = await navigator.mediaDevices.getUserMedia({ video: true });
    camera.start();
})();

// Event listener for gesture detection
hands.onResults(results => {
    if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
        const hand = results.multiHandLandmarks[0];

        // Simple palm open detection based on the distance between fingers
        const isOpenPalm = checkOpenPalm(hand);

        if (isOpenPalm) {
            window.location.href = 'secondPage.html';
        }
    }
});

// Check if the hand is open based on landmarks (simple heuristic)
function checkOpenPalm(hand) {
    const thumbTip = hand[4]; // Tip of the thumb
    const indexTip = hand[8]; // Tip of the index finger
    const pinkyTip = hand[20]; // Tip of the pinky finger

    // Calculate the distances between the thumb, index, and pinky finger tips
    const thumbIndexDist = Math.hypot(thumbTip.x - indexTip.x, thumbTip.y - indexTip.y);
    const indexPinkyDist = Math.hypot(indexTip.x - pinkyTip.x, indexTip.y - pinkyTip.y);

    // Debugging: Print the distances
    console.log('Thumb to Index Distance:', thumbIndexDist);
    console.log('Index to Pinky Distance:', indexPinkyDist);

    // Check if the fingers are spread apart (you can adjust the threshold values)
    return thumbIndexDist > 0.2 && indexPinkyDist > 0.4;
}
