/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var socket;
function init() {
     var host = "ws://121.42.12.97:8585/";
     try {
         socket = new WebSocket(host);
         socket.onopen = function (msg) {; };
         socket.onmessage = function (msg) { log(msg.data); };
         socket.onclose = function (msg) { ; };
     }
     catch (ex) { log(ex); }
     $("msg").focus();
 }

 function send() {
     var txt, msg;
     txt = $("msg");
     msg = txt.value;
     if (!msg) { alert("Message can not be empty"); return; }
     txt.value = "";
     txt.focus();
     try { socket.send(msg); } catch (ex) { log(ex); }
 }

 window.onbeforeunload = function () {
     try {
         socket.send('quit');
         socket.close();
         socket = null;
     }
     catch (ex) {
         log(ex);
     }
 };

/*
 function $(id) { 
     return document.getElementById(id); 
 }

 function log(msg) { 
     $("log").innerHTML += "<br>" + msg; 
 }

 function onkey(event) { 
     if (event.keyCode == 13) { send(); } 
 }*/

