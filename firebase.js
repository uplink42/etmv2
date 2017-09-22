const messaging = firebase.messaging();
requestPermission();
init();

messaging.onTokenRefresh(function() {
    messaging.getToken().then(function(refreshedToken) {
        console.log('Token refreshed.');
        setTokenSentToServer(false);
        sendTokenToServer(refreshedToken);
    }).catch(function(err) {
        console.log('Unable to retrieve refreshed token ', err);
        showToken('Unable to retrieve refreshed token ', err);
    });
});

messaging.onMessage(function(payload) {
    console.log("Message received. ", payload);
    var notification = new Notification(payload.notification.title, {
        icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
        body: payload.notification.title,
    });
    notification.onclick = function() {
        window.open("http://stackoverflow.com/a/13328397/1269037");
    };
});


function showToken(currentToken) {
    console.log('token', currentToken);
}

function sendTokenToServer(currentToken) {
    if (!isTokenSentToServer()) {
        console.log('token', currentToken);
        console.log('Sending token to server...');
        setTokenSentToServer(true);
    } else {
        console.log('token', currentToken);
        console.log('Token already sent to server so won\'t send it again ' + 'unless it changes');
    }
}

function isTokenSentToServer() {
    return window.localStorage.getItem('sentToServer') == 1;
}

function setTokenSentToServer(sent) {
    window.localStorage.setItem('sentToServer', sent ? 1 : 0);
}

function requestPermission() {
    console.log('Requesting permission...');
    messaging.requestPermission().then(function() {
        console.log('Notification permission granted.');
    }).catch(function(err) {
        console.log('Unable to get permission to notify.', err);
    });
}

function init() {
    messaging.getToken()
    .then(function(currentToken) {
        if (currentToken) {
            sendTokenToServer(currentToken);
        } else {
            console.log('No Instance ID token available. Request permission to generate one.');
            updateUIForPushPermissionRequired();
            setTokenSentToServer(false);
        }
    })
    .catch(function(err) {
        console.log('An error occurred while retrieving token. ', err);
        showToken('Error retrieving Instance ID token. ', err);
        setTokenSentToServer(false);
    });
}


