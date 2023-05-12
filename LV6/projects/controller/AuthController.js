const passport = require("passport");
const { genPassword } = require("../database/passwordUtils");
const User = require("../model/user");

exports.login = (req, res) => {
    res.status(200).json({ user: {id: req.user.id, username: req.user.username}, msg: 'Successfully logged in!'});
}

exports.user = (req, res) => {
    if(req.isAuthenticated()){
        res.status(200).json( { id: req.user.id, username: req.user.username} );
    }else{
        res.status(401).send('Unauthorized');
    }
}

exports.logout = (req, res) => {
    if(req.user){
        req.logout();
        res.status(200).send('You are logged out!');
    }
    else{
        res.status(401).send('Unauthorized');
    }
    
}

exports.register = (req, res) => {
    // Validate request
    if(!req.body){
        res.status(400).send({ message: "Content can not be empty!"});
        return;
    }

    const user = User.find({username: req.body.username})
        .then(user => {
            if(user.length){
                return res.status(400).send({message:'User with this name already exists!'});
            }
            else{
                const saltHash = genPassword(req.body.password);
                const newUser = new User({
                    username : req.body.username,
                    hash: saltHash.hash,
                    salt: saltHash.salt
                })

                newUser.save()
                    .then((user) => {
                        req.login(user, err =>{
                            if(err){
                                console.log(err);
                            }
                            return res.status(200).send({user: {id: user._id, username: user.username}, message: 'Successfully registered!'});
                        })
                    })
                    .catch(err => {
                        res.status(500).send({ message: `Error registering user!`});
                    })
            }
        })
        .catch(err => {
            console.log(err);
        });

}