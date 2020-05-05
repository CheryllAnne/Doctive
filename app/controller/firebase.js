//sync Users
exports.syncUsers = functions.database.ref('/Users/{userId}')
    .onWrite(event => {
        // Grab the current value of what was written to the Realtime Database.
        var userId = event.params.userId;
        var eventSnapshot = event.data;
        // Exit when the data is deleted.
        if (!event.data.exists()) {
            console.log("DELETE User by Id:" + userId);
            var DELETE_USER_SQL = "DELETE FROM `Users` where `userId` = ?";
            var params = [
                userId
            ];
            var connection;
            return mysql.createConnection(dbconfig).then(function(conn){
                connection = conn;
                return connection.query(DELETE_USER_SQL, params);
            })

        }
        console.log("INSERT/UPDATE User by Id:" + userId);
        var INSERT_USER_SQL = "INSERT INTO `Users` (`userId`, `age`, `firstName`, `lastName`, `phone` ) VALUES (?, ?, ?, ?, ?)";
        var params = [
            userId,
            eventSnapshot.child("age") ? eventSnapshot.child("age").val() : null,
            eventSnapshot.child("firstName") ? 	eventSnapshot.child("firstName").val() : null,
            eventSnapshot.child("lastName") ? eventSnapshot.child("lastName").val() : null,
            eventSnapshot.child("phone") ? 	eventSnapshot.child("phone").val() : null
        ];
        return mysql.createConnection(dbconfig).then(function(conn){
            connection = conn;
            return connection.query(INSERT_USER_SQL, params);
        });
    });