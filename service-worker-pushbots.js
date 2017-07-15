var logging = true,
	application_id = "5969d3bb4a9efaa3b08b4568",
	pushbots_url = "https://api.pushbots.com/";

self.addEventListener('install', function(event) {
	event.waitUntil(self.skipWaiting());
});

self.addEventListener('push', function(event) {  
	event.waitUntil(  
		fetch(pushbots_url + "pushnotification/" + application_id).then(function(response) {
			return response.json().then(function(data) {
				// Notification URL if sent through Pushbots
				var uri = "";
				if(data.p){
					if(data.p.chrome_url_data){
						uri = encodeURIComponent(data.p.chrome_url_data);
					}
				}
				
				var title = 'Eve Trade Master';
				var body = data.m;
				var icon = 'app-icons/android-chrome-512x512.png?' + uri;
				var tag = 'pushbotstag';
				
				return self.registration.showNotification(title, {  
					body: body,  
					icon: icon,  
					tag: tag  
				}); 
			});
		})
	);
});

self.addEventListener('notificationclick', function(event) {
	if(logging) console.log('On notification click open URL: ', event.notification.icon.split("?") );
	
	// Android doesn't close the notification when you click on it  
	// See: http://crbug.com/463146  
	event.notification.close();
	
	// This looks to see if the current is already open and  
	// focuses if it is  
	event.waitUntil(
		clients.matchAll({  
			type: "window"  
		})
		.then(function(clientList) {
			var urlToOpen = event.notification.icon.split("?")[1];
			
			//Open URL in notification payload if exists
			if(urlToOpen != ""){
				return clients.openWindow(decodeURIComponent(urlToOpen));
			}
			
			for (var i = 0; i < clientList.length; i++) {  
				var client = clientList[i];  
				if (client.url == '/' && 'focus' in client)  
					return client.focus();  
			}
			
			if (clients.openWindow) {
				return clients.openWindow('/');  
			}
		})
	);
});