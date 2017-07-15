//Service worker file name
var serviceWorkerFile = './service-worker-pushbots.js',
	logging = true,
	application_id = "5969d3bb4a9efaa3b08b4568",
	pushbots_url = "https://api.pushbots.com/";

// Once the service worker is registered set the initial state  
function initialise() { 
	
	var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
	var push_supported = (!!('PushManager' in window) || !!navigator.push) 
	&& !!window.Notification
	&& !!navigator.serviceWorker
	&& !!('showNotification' in ServiceWorkerRegistration.prototype);
	
	//Check if Push messaging && serviceWorker && Notifications are supported in the browser
	if(push_supported && isChrome){
		
		var notificationStatusSetBefore = Notification.permission != "default";
			
		if(logging) console.log("Notification permission status:", Notification.permission);
		
		Notification.requestPermission(function(result) {
			if (result === 'denied') {
				if(logging) console.log('Permission was denied.');
				return;
			}else if( result === 'granted'){
			  	//var notification = new Notification("Hi there!");
				//Register Notifications serviceWorker
				navigator.serviceWorker.register(serviceWorkerFile).then(function(serviceWorkerRegistration) {
					
					if(logging) console.log('Yey!', serviceWorkerRegistration);
					
					if (notificationStatusSetBefore) {
						serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true}).then(function(subscription) {  
							// The subscription was successful 
							if(logging) console.log(subscription);
				  
							var token = endpointWorkaround(subscription);
							
							//Register the token on Pushbots
							try {
							    var xmlhttp = new XMLHttpRequest();
								var jsonBody = JSON.stringify({
									"token":token,
									"platform":2,
									"tags": ['bob'],
								
									"locale" : window.navigator.language || 'en'
								});
								
							    xmlhttp.open('PUT', pushbots_url  + 'deviceToken');

							    xmlhttp.setRequestHeader('X-pushbots-appid', application_id);
							    xmlhttp.setRequestHeader('content-type', 'application/json; charset=UTF-8');

								xmlhttp.onload = function(){
									if(this.status === 200){
										console.log("Updated info on Pushbots successfully.");
									}else if(this.status === 201){
										console.log("Registered on Pushbots successfully.");
									}else{
										console.warn("Status code" + this.status + ": error.")
									}
								};
								
								xmlhttp.onerror = function(e){
									console.log("Error occured: ", e);
								};
	
							    xmlhttp.send(jsonBody);
	
							} catch(e) {
								console.log('Cannot register on Pushbots: ' + e);
							}
							
							
							
						}).catch(function(e) {  
							if (Notification.permission === 'denied') {  
								// The user denied the notification permission which  
								// means we failed to subscribe and the user will need  
								// to manually change the notification permission to  
								// subscribe to push messages  
								if(logging) console.warn('Permission for Notifications was denied');  
							} else {  
								// A problem occurred with the subscription; common reasons  
								// include network errors, and lacking gcm_sender_id and/or  
								// gcm_user_visible_only in the manifest.  
								if(logging) console.error('Unable to subscribe to push.', e);
							}  
						});
					}else{
						//Refresh the page to enable serviceWorker for the first time
						setTimeout(function() {
							window.location.href = window.location.href;
						}, 200);
					}
				  
				}).catch(function(err) {
					if(logging) console.log('Boo!', err);
				});
			  
			}
		});
	}else{
		console.warn("Push messaging is not supported in this browser.");
	}
}

// This method handles the removal of subscriptionId
// in Chrome 44 by concatenating the subscription Id
// to the subscription endpoint
function endpointWorkaround(pushSubscription) {
	// Chrome 42 + 43 will not have the subscriptionId attached
	// to the endpoint.
	if (pushSubscription.subscriptionId) {
		// Handle version 42 where you have separate subId and Endpoint
		return pushSubscription.subscriptionId;
	}else{
		return pushSubscription.endpoint.split('/').pop();
	}
}

//Initialize Notifications
if ( document.readyState === "complete" ) {
	initialise();
}else{
	// Mozilla, Opera and webkit nightlies currently support this event
	if ( document.addEventListener ) {
		window.addEventListener( "load", initialise, false );
		// If IE event model is used
	} else if ( document.attachEvent ) {
		// ensure firing before onload,
		// maybe late but safe also for iframes
		document.attachEvent("onreadystatechange", function(){
			if ( document.readyState === "complete" ) {
				initialise();
			}
		});
	
	}
}
