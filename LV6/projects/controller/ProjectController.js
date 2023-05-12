const Projectdb = require('../model/project');

// Retrieve and return all projects / retrieve and return a single project
exports.find = (req,res)=>{
    if(!req.isAuthenticated()){
        return res.status(401).send('You are not logged in');
    }

    if(req.query.id){
        const id = req.query.id;

        Projectdb.findById(id)
            .then(data=>{
                if(!data){
                    res.status(404).send({
                        message: `Project with ${id} not found!`
                    });
                }else{
                    res.json(data);
                }
            })
            .catch(err => {
                res.status(500).send({ message: `Error retrieving project with id ${id}`});
            })
    }else{
        Projectdb.find()
            .then(project => {
                res.json(project)
            })
            .catch(err =>{
                res.status(500).send({ message: err.message || "Error occurred while retrieving project information!"});
            })
    }
}

// Create and save a new project
exports.create = (req,res)=>{
    if (!req.isAuthenticated()){
        res.status(401).send({message: 'You are not logged in!'});
    }

    // validate request
    if(!req.body){
        res.status(400).send({ message: "Content can not be empty!"});
        return;
    }

    // new project
    const project = new Projectdb({
        name : req.body.name,
        price: req.body.price,
        tasks_done: '',
        description: req.body.description,
        created_at: new Date(Date.now()),
        updated_at: new Date(Date.now()),
        owner: {
            id: req.user._id,
            username: req.user.username
        },
        members: [],
    });

    // save project in db
    project
        .save(project)
        .then(data => {
            res.send(data)
        })
        .catch(err =>{
            res.status(500).send({
                message: err.message || "Some error occurred while creating a new project!"
            });
        });
}


// Update a project by id
exports.update = (req, res) => {
    if(!req.isAuthenticated()){
        return res.status(401).send('You are not logged in');
    }

    if(!req.body){
        return res
            .status(400)
            .send({message:"Data to update can not be empty!"});
    }

    const id = req.params.id;

    Projectdb.findById(id)
        .then(project => {
            if(project.finished_at){
                // Project is archived and cannot be updated
                return res.send(project);
            }
            // Update updated_at attribute
            project.updated_at = new Date(Date.now());
            // Only owner can update everything on project
            if(project.owner.id === req.user._id.toString()){
                updateProject(id, req.body, res)
            }
            // Members can change only tasks_done
            else if(project.members.find(member => member._id.equals(req.user._id))){
                project.tasks_done = req.body.tasks_done
                updateProject(id, project, res);
            }
            else{
                // Current user isn't a project member
                res.status(401).send('Unauthorized');
            }
        })
}

function updateProject(id, data, res){
    Projectdb.findByIdAndUpdate(id, data, { new: true})
        .then(project => {
            if(!project){
                res.status(404).send({ message: `Cannot update project with ${id}.`})
            }else{
                res.send(project);
            }
        })
        .catch(err => {
            res.status(500).send({message: "Invalid project information"});
        });
}

function deleteProject(id, res){
    Projectdb.findByIdAndDelete(id)
        .then(data => {
            if(!data){
                res.status(404).send({ message: `Cannot delete with id ${id}. Maybe id is wrong.`});
            }else{
                res.send({
                    message: "Project was deleted successfully!"
                });
            }
        })
        .catch(err =>{
            res.status(500).send({
                message: `Could not delete project with ${id}`
            })
        })
}

// Delete a project by id
exports.delete = (req, res) => {
    if(!req.isAuthenticated()){
        return res.status(401).send('You are not logged in');
    }

    const id = req.params.id;
    Projectdb.findById(id)
        .then(data => {
            if(data.owner.id !== req.user._id.toString()){
                return res.status(401).send({message: 'Only owner can delete the project'});
            }
            else{
                deleteProject(id, res);
            }
        }).catch(err => {
            res.status(500).send({ message: `Error retrieving project with id ${id}`});
        })
    
}

exports.archive = (req, res) => {
    if(!req.isAuthenticated()){
        return res.status(401).send('You are not logged in');
    }

    const id = req.params.id;

    // Get project by id
    Projectdb.findById(id)
        .then(data => {
            if(!data){
                res.status(404).send({
                    message: `Project with ${id} not found!`
                });
            }else{
                if(data.finished_at){
                    return res.status(400).send({message: 'Project is already archived'});
                }
                else if(data.owner.id !== req.user._id.toString()){
                    return res.status(401).send({message: 'Only owner can archive projects!'});
                }else{
                    data.finished_at = new Date(Date.now());
                    // Update the project with archived date
                    updateProject(id, data, res);
                }
            }
        })
        .catch(err => {
            res.status(500).send({ message: `Error retrieving project with id ${id}`});
        })

    
}
