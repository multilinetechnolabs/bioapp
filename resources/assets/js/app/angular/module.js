require('angular-filter');
require('angular-resource');

var API = {}
if (document.querySelector("meta[name='api-host']")) {
    API.HOST = document.querySelector("meta[name='api-host']").content
}
if (document.querySelector("meta[name='api-version']")) {
    API.VERSION = document.querySelector("meta[name='api-version']").content
}
if (document.querySelector("meta[name='api-token']")) {
    API.TOKEN = document.querySelector("meta[name='api-token']").content
}

var app = angular.module('AnewApp', ['ngResource', 'angular.filter']);
app.constant('API_HOST', API.HOST);
app.constant('API_VERSION', API.VERSION);
app.constant('API_PREFIX', [API.HOST, API.VERSION].join('/'));
app.constant('HEADERS', { 
    'Authorization': 'Bearer ' + API.TOKEN
});

require('../angular/configs');
require('../angular/filters');
require('../angular/factories');
require('../angular/controllers');
