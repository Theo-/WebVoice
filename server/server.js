/*global console*/
var yetify = require('yetify'),
    config = require('getconfig'),
    uuid = require('node-uuid'),
    io = require('socket.io').listen(config.server.port);

var rooms=new Array();
var referencedClient=new Array();

function describeRoom(name) {
    var clients = io.sockets.clients(name);
    var result = {
        clients: {}
    };
    clients.forEach(function (client) {
        result.clients[client.id] = client.resources;
    });
    return result;
}

function safeCb(cb) {
    if (typeof cb === 'function') {
        return cb;
    } else {
        return function () {};
    }
}

io.sockets.on('connection', function (client) {
    client.resources = {
        screen: false,
        video: true,
        audio: false
    };
    client.id = null;

    // pass a text message to all the room
    client.on("text_message", function(data) {
        var message = data.text;

        var clients = io.sockets.clients(client.room);
        var data_to_send = {
            from: client.id,
            text: data.text,
            time: new Date()
        };

        io.sockets.in(client.room).emit("text_message_echo", data_to_send);
    });

    client.on("add_reference", function(data) {
        if(referencedClient[data.id]) {return;}

        referencedClient[data.id] = client;
    });

    // pass a message to another id
    client.on('message', function (details) {
        var otherClient = referencedClient[details.to];
        if (!otherClient) return;
        details.from = client.id;
        otherClient.emit('message', details);
    });

    client.on('shareScreen', function () {
        client.resources.screen = true;
    });

    client.on('unshareScreen', function (type) {
        client.resources.screen = false;
        if (client.room) removeFeed('screen');
    });

    function removeFeed(type) {
        io.sockets.in(client.room).emit('remove', {
            id: client.id,
            type: type
        });
    }

    function join(name, passwd, cb) {
        // sanity check
        if (typeof name !== 'string') return;
        // if room is accessed without being created
        if(!rooms[name]) {
            create(name, cb);
            return;
        }
        // leave any existing rooms
        if (client.room) removeFeed();
        // password check
        if (rooms[name].passwd_enabled
        	&& (typeof passwd === 'undefined' || rooms[name].passwd != passwd)) {
            safeCb(cb)('invalid password', null);
            disconnect();
            return;
        }
        
        safeCb(cb)(null, describeRoom(name));
        client.join(name);
        client.room = name;
    }
    client.on('join', join);

    // we don't want to pass "leave" directly because the
    // event type string of "socket end" gets passed too.
    function disconnect() {
        removeFeed();
    };
    client.on('disconnect', disconnect);
    
    client.on('leave', removeFeed);
    
    function create(name, cb) {
        if (arguments.length == 2) {
            cb = (typeof cb == 'function') ? cb : function () {};
            name = name || uuid();
        } else {
            cb = name;
            name = uuid();
        }
        // check if exists
        if (io.sockets.clients(name).length) {
            safeCb(cb)('taken');
        } else {
            rooms[name]={
                creator: client.id,
                passwd: '',
                passwd_enabled: false
            };
            join(name);
            safeCb(cb)(null, name);
        }
    };
    client.on('create', create);
    
    client.on('chgpasswd', function (passwd) {
        if (rooms[client.room]
                && rooms[client.room].creator == client.id) {
            rooms[client.room].passwd = passwd;
            console.log('chgpasswd : '+passwd);
            return;
        }
        console.log('chgpasswd failed');
    });
    
    client.on('actpasswd', function (bool) {
        if (rooms[client.room]
                && rooms[client.room].creator == client.id) {
            rooms[client.room].passwd_enabled = bool;
            console.log('actpasswd : '+bool);
            return;
        }
        console.log('actpasswd failed '+rooms[client.room]+' '+client.room);
    });
});

if (config.uid) process.setuid(config.uid);
console.log(yetify.logo() + ' -- signal master is running at: http://localhost:' + config.server.port);
