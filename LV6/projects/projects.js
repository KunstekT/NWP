const express = require('express');
const dotenv = require('dotenv');
const morgan = require('morgan');
const bodyparser = require("body-parser");
const path = require('path');
const cors = require('cors');
const connectDB = require('./server/database/connection');

const app = express();

dotenv.config({path : 'config.env'});
const PORT = process.env.PORT || 8080;

app.use(morgan('tiny'));

connectDB();

app.use(bodyparser.urlencoded({extended: true}))
app.use(bodyparser.json());
app.use(cors());
app.use('/', require('./server/routes/router'))

app.listen(PORT, ()=>{console.log(`Server is running on http://localhost:${PORT}`)});