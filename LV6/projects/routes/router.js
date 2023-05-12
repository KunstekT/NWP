const express = require('express');
const passport = require('passport');
const router = express.Router();

const projectController = require('../controller/ProjectController');
const authController = require('../controller/AuthController');
const userController = require('../controller/UserController');

router.get('/', (req, res) => {
    console.log(req.user);
    res.send('Crud App!')
});

router.get('/api/projects', projectController.find);
router.post('/api/projects', projectController.create);
router.put('/api/projects/:id', projectController.update);
router.delete('/api/projects/:id', projectController.delete);
router.post('/api/archive/:id', projectController.archive)

router.post('/api/login', passport.authenticate('local'), authController.login);
router.get('/api/user', authController.user);
router.get('/api/logout', authController.logout);
router.post('/api/register', authController.register);

router.get('/api/users', userController.users);

module.exports = router;