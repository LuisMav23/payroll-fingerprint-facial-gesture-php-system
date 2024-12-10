const videoElement = document.getElementById('video');

// Initialize MediaPipe Hands
const hands = new Hands({
    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
});

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

// Start the camera and video stream
(async () => {
    videoElement.srcObject = await navigator.mediaDevices.getUserMedia({ video: true });
    camera.start();
})();

// Detect gestures
hands.onResults(results => {
    if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
        const hand = results.multiHandLandmarks[0];

        // Check if the palm is open
        const isOpenPalm = checkOpenPalm(hand);

        if (isOpenPalm) {
            window.location.href = 'next.html';
        }
    }
});

// Function to check if the hand is an open palm
function checkOpenPalm(hand) {
    const thumbTip = hand[4]; // Tip of the thumb
    const indexTip = hand[8]; // Tip of the index finger
    const pinkyTip = hand[20]; // Tip of the pinky finger

    // Calculate distances between fingers
    const thumbIndexDist = Math.hypot(thumbTip.x - indexTip.x, thumbTip.y - indexTip.y);
    const indexPinkyDist = Math.hypot(indexTip.x - pinkyTip.x, indexTip.y - pinkyTip.y);

    // Threshold values to detect if the hand is open
    return thumbIndexDist > 0.2 && indexPinkyDist > 0.4;
}
