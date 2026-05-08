const express = require('express');
const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

app.get('/', (req, res) => {
    res.json({ message: 'Hello from Node server!' });
});

app.get('/health', (req, res) => {
    res.json({ status: 'ok' });
});

app.listen(PORT, () => {
    console.log(`Node server is running on port ${PORT}`);
});
