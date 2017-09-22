//some default pre init
var PB = PB || {};PB.q = PB.q || [];PB.events = PB.events || [];

//********** Update these fields ********
//PushBots ApplicationId (required)
PB.app_id = "APPLICAITON_ID";
//Your domain name, must be HTTPS or localhost  (required)
PB.domain = "https://www.evetrademaster.com";
//Update and uncomment it if you are using custom safari certificate for your app
//PB.safari_push_id = "web.com.pushbots.main";
//****************************************

PB.logging_enabled = false;
PB.auto_subscribe = true;

//Custom worker and manifest URL
//PB.worker_url = PB.domain + "/pushbots-worker.js";
//PB.manifest_url = PB.domain + "/pushbots-push-manifest.json";

//Welcome notification message
PB.welcome = {title:"Welcome 🙌🎉",message:"Thanks for subscribing!", url :PB.domain};

function sendNotification(){
     PB.register();
     PB.q.push(["sendNotification", {title:"Hey 🐬",message:"Why not?", url :"https://google.com"}]);
}