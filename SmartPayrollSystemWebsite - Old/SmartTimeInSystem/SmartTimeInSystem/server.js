const express = require('express');
const path = require('path');

const app = express();
const port = 1337;

// Serve static files
app.use(express.static(path.join(__dirname, 'public')));

// Handle requests for the second page
app.get('/secondPage', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'secondPage.html'));
});

// Start the server
app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
