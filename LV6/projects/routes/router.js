const express = require('express');
const router = express.Router();
const projectController = require('../controller/ProjectController');

router.get('/', (req, res) => {
    res.send('CRUD')
});

router.get('/api/papi', (req, res) => {
    res.send('CRUD')
})

router.get('/api/projects', projectController.find);
router.post('/api/projects', projectController.create);
router.put('/api/projects/:id', projectController.update);
router.delete('/api/projects/:id', projectController.delete);

module.exports = router;