const mongoose = require('mongoose');

var schema = new mongoose.Schema({
    name:{
        type: String,
        required: true
    },
    price:{
        type: Number,
        required: true
    },
    tasks_done: String,
    description: String,
    created_at: {
        type: Date,
        required: true
    },
    updated_at: {
        type: Date,
        required: true
    },
    members: String
})

const Projectdb = mongoose.model('projectdb', schema);

module.exports = Projectdb;