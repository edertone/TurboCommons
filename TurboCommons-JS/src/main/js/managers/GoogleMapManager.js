"use strict";

/**
 * Class that allows us to manage a google map
 * We must create one instance of this class for each google map we want to place on our application.
 * 
 * import path: 'js/libs/libEdertoneJS/managers/GoogleMapManager.js'
 */
var GoogleMapManager = function(){


	/** Stores the google map object that is created by this class */
	this.map = null;


	/** Stores the list of markers that have been added to this map instance */
	this.markers = [];


	/** Stores the list of info windows that are related to the markers array. Elements are placed in the same order as their related marker. */
	this.infoWindows = [];


	/** 
	 * Specifies if more than one info windows can be opened at the same time for the map markers. 
	 * If set to false, clicking on a map marker will close any opened infowindows for other map markers.
	 * If a marker does not have an assigned info window, a null value will be set to its same position on this array. 
	 */
	this.multipleInfoWindows = false;

};


/**
 * Generate a google map on the specified html container (normally a div).
 * This is the first method that needs to be called to create a new google map. We will then add markers and other things with the rest of this class methods.
 * 
 * @param elementId The div id where the map will be generated
 * @param latitude The latitude value for the center of the map
 * @param longitude longitude The longitude value for the map
 * @param zoom The zoom value for the map. 15 by default
 * @param draggable Lets the user drag the map position or not. Normally useful to disable it for mobile devices, to prevent the page scroll from being locked when the map is dragged. false by default as all sites are normally aimed to mobile devices.
 * 
 * @returns The generated google maps object instance
 */
GoogleMapManager.prototype.createMap = function(elementId, latitude, longitude, zoom, draggable){

	// Set default values if they are not defined
	zoom = zoom === undefined ? 15 : zoom;
	draggable = draggable === undefined ? false : draggable;

	// Check that the google maps library is imported
	if(typeof google === 'undefined'){

		throw new Error("GoogleMapsUtils.createMap - Google api not imported. Use this path: https://maps.google.com/maps/api/js?sensor=false");
	}

	// Check that the specified element exists
	if(!document.getElementById(elementId)){

		throw new Error("GoogleMapsUtils.createMap - Specified map container does not exist: " + elementId);
	}

	// Check that latitude and longitude are defined
	if(latitude === undefined || longitude === undefined){

		throw new Error("GoogleMapsUtils.addMarker - Latitude and longitude must be defined");
	}

	// Create a map options object
	var mapOptions = {
		zoom : zoom,
		center : new google.maps.LatLng(latitude, longitude),
		mapTypeId : google.maps.MapTypeId.ROADMAP,
		draggable : draggable,
		scrollwheel : draggable
	};

	// Create the map
	this.map = new google.maps.Map(document.getElementById(elementId), mapOptions);

	return this.map;
};

/**
 * Adds a standard google maps marker to the map object
 * 
 * @param latitude The latitude where the marker will be placed
 * @param longitude The longitude where the marker will be placed
 * @param infoWindowHtml The info window content as html code. This window is a little popup informative window that appears pointing to the map marker. If it's not defined, the info window won't be shown
 * @param infoWindowOpened True by default. If set to false, the content Html window will not be opened by default, and we will need to click on the marker to open it
 * 
 * @returns object The created marker instance
 */
GoogleMapManager.prototype.addMarker = function(latitude, longitude, infoWindowHtml, infoWindowOpened){

	// Set default values if they are not defined
	infoWindowHtml = infoWindowHtml === undefined ? '' : infoWindowHtml;
	infoWindowOpened = infoWindowOpened === undefined ? true : infoWindowOpened;

	// Check that google map exists
	if(this.map == null){

		throw new Error("GoogleMapsUtils.addMarker - map instance has not been initialized. Use GoogleMapsUtils.createMap");
	}

	// Check that latitude and longitude are defined
	if(latitude === undefined || longitude === undefined){

		throw new Error("GoogleMapsUtils.addMarker - Latitude and longitude must be defined");
	}

	// Create the marker
	var marker = new google.maps.Marker({
		map : this.map,
		position : new google.maps.LatLng(latitude, longitude)
	});

	// Create the info window only if there is a defined contentHtml
	if(infoWindowHtml != ''){

		var infoWindow = new google.maps.InfoWindow();

		infoWindow.setContent(infoWindowHtml);

		if(infoWindowOpened){

			if(!this.multipleInfoWindows){

				this.closeAllInfoWindows();
			}

			infoWindow.open(this.map, marker);
		}

		var parent = this;

		google.maps.event.addListener(marker, 'click', function(){

			if(!parent.multipleInfoWindows){

				parent.closeAllInfoWindows();
			}

			infoWindow.open(parent.map, marker);
		});

		this.infoWindows.push(infoWindow);

	}else{

		this.infoWindows.push(null);
	}

	// Add the created marker to the list of class markers
	this.markers.push(marker);

	return marker;
};


/**
 * Adds a marker to the map object. The marker is customized with the specified icon.
 * 
 * @param latitude The latitude where the marker will be placed
 * @param longitude The longitude where the marker will be placed
 * @param iconUrl The url where the custom icon for the marker is located.
 * @param infoWindowHtml The info window content as html code. This window is a little popup informative window that appears pointing to the map marker. If it's not defined, the info window won't be shown
 * @param infoWindowOpened True by default. If set to false, the content Html window will not be opened by default, and we will need to click on the marker to open it
 * 
 * @returns object The created marker instance
 */
GoogleMapManager.prototype.addIconMarker = function(latitude, longitude, iconUrl, infoWindowHtml, infoWindowOpened){

	// Set default values if they are not defined
	infoWindowHtml = infoWindowHtml === undefined ? '' : infoWindowHtml;
	infoWindowOpened = infoWindowOpened === undefined ? true : infoWindowOpened;

	// Check that google map exists
	if(this.map == null){

		throw new Error("GoogleMapsUtils.addIconMarker - map instance has not been initialized. Use GoogleMapsUtils.createMap");
	}

	// Check that latitude and longitude are defined
	if(latitude === undefined || longitude === undefined){

		throw new Error("GoogleMapsUtils.addIconMarker - Latitude and longitude must be defined");
	}

	// Create the marker
	var marker = new google.maps.Marker({
		map : this.map,
		icon : iconUrl,
		position : new google.maps.LatLng(latitude, longitude)
	});

	// Create the info window only if there is a defined contentHtml
	if(infoWindowHtml != ''){

		var infoWindow = new google.maps.InfoWindow();

		infoWindow.setContent(infoWindowHtml);

		if(infoWindowOpened){

			if(!this.multipleInfoWindows){

				this.closeAllInfoWindows();
			}

			infoWindow.open(this.map, marker);
		}

		var parent = this;

		google.maps.event.addListener(marker, 'click', function(){

			if(!parent.multipleInfoWindows){

				parent.closeAllInfoWindows();
			}

			infoWindow.open(parent.map, marker);
		});

		this.infoWindows.push(infoWindow);

	}else{

		this.infoWindows.push(null);
	}

	// Add the created marker to the list of class markers
	this.markers.push(marker);

	return marker;
};


/**
 * Adds a standard google maps marker to the map object
 * 
 * @returns void
 */
GoogleMapManager.prototype.closeAllInfoWindows = function(){

	for(var i = 0; i < this.infoWindows.length; i++){

		this.infoWindows[i].close();
	}
};


/**
 * Given a latitude and a longitude, this method will return the google maps marker object that is closer to the given point 
 * 
 * @param latitude The latitude value we want to find the closest marker object
 * @param longitude The longitude value we want to find the closest marker object
 * 
 * @returns object A javscript google maps object representing the marker that is closer to the specified latitude and longitude
 */
GoogleMapManager.prototype.getNearestMarker = function(latitude, longitude){

	// Check that google map exists
	if(this.map == null){

		throw new Error("GoogleMapsUtils.getNearestMarker - map instance has not been initialized. Use GoogleMapsUtils.createMap");
	}

	// Check that latitude and longitude are defined
	if(!$.isNumeric(latitude) || !$.isNumeric(longitude)){

		throw new Error("GoogleMapsUtils.getNearestMarker - Latitude and longitude must be defined");
	}

	// radius of earth in km
	var R = 6371;

	// Method to calculate a raduis
	function rad(x){

		return x * Math.PI / 180;
	}

	var distances = [];
	var closest = -1;

	for(var i = 0; i < this.markers.length; i++){

		var mlat = this.markers[i].position.lat();
		var mlng = this.markers[i].position.lng();
		var dLat = rad(mlat - latitude);
		var dLong = rad(mlng - longitude);
		var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(rad(latitude)) * Math.cos(rad(latitude)) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
		var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
		var d = R * c;
		distances[i] = d;

		if(closest == -1 || d < distances[closest]){
			closest = i;
		}
	}

	return this.markers[closest];
};