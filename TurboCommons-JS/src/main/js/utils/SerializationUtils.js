"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


/* exported SerializationUtils */

/**
 * Static class with complex object conversion utilities
 * 
 * import path: 'js/libs/libEdertoneJS/utils/SerializationUtils.js'
 */
var SerializationUtils = {


	/**
	 * Converts a received class object to its xml representation. It is recursive, so it will also convert contained arrays and objects.
	 *
	 * @param classInstance An instance of the class to convert
	 * @param className We can optionally give the root classname for the given instance if we know it. If not specified, the method will try to detect it, but normally the class will be detected as "Object"
	 *
	 * @return A jquery element representing the XML data, wich we can operate as any other jquery object (append, remove nodes etc...). To convert it to an xml string, simply use SerializationUtils.xmlToString
	 */
	classToXml : function(classInstance, className){

		// Set optional parameters default values
		className = (className === undefined) ? '' : className;

		// Try to detect the given instance class name
		if(className == ''){

			className = SerializationUtils.objectToClassName(classInstance);
		}

		var xml = $($.parseXML("<" + className + "/>"));

		var props = Object.getOwnPropertyNames(classInstance);

		for(var i = 0; i < props.length; i++){

			if(Object.prototype.toString.call(classInstance[props[i]]) === '[object Array]'){

				// TODO: part en la que la propietat es un array

			}else{

				if(typeof (classInstance[props[i]]) != "function"){

					xml.find(className).attr(props[i], classInstance[props[i]]);
				}
			}
		}

		return xml;
	},


	/**
	 * Fills an instance of a class from all the data on the specified form that matches the class properties
	 * 
	 * @param form A jquery object representing the form from which we want to extract the data. We can pass an htm form element, or also a div containing inputs, buttons, and so.
	 * @param class The class instance that will be filled with the source data
	 * 
	 * @returns The provided class instance
	 */
	formToClass : function(form, classInstance){

		return SerializationUtils.objectToClass(SerializationUtils.formToObject(form), classInstance);
	},


	/**
	 * Converts the data from an existing form to a javascript object.
	 * Data from all form inputs, textareas, checkboxes, etc.. is stored on the resulting object by using the original element name or id as the object property, and the value as the object value.
	 * Note that if source name attribute is defined, it will always take precedence over the id value. If no name attribute is found, id will be used.
	 * 
	 * @param form A jquery object representing the form from which we want to extract the data. We can pass an htm form element, or also a div containing inputs, buttons, and so. 
	 * 
	 * @returns Object A javascript object with each relevant form elements stored as property->value pairs. (property is taken from the source element name. If name is missing, id will be used)
	 */
	formToObject : function(form){

		var elementId = '';
		var res = {};

		// Loop all the form elements
		form.find(':input,textarea').each(function(){

			if(!$(this).is(':submit')){

				elementId = $(this).attr('id');

				// Name attribute takes precedence over id attribute
				if($(this).attr('name') !== '' && $(this).attr('name') !== undefined){

					elementId = $(this).attr('name');
				}

				if(elementId == '' || elementId === undefined){

					throw new Error("SerializationUtils.formToObject - Form element is missing name or id for serialization");
				}

				// Store the element value on the result object
				if($(this).is(':checkbox')){

					res[elementId] = $(this).is(":checked");

				}else{

					res[elementId] = $(this).val();
				}
			}
		});

		return res;
	},


	/**
	 * Convert a JSON string to the respective javascript object
	 * 
	 * @param string The JSON string that contains the defined object
	 * 
	 * @returns Object
	 */
	jsonToObject : function(string){

		if(string == ""){
			return {};
		}

		var res = JSON.parse(string);

		return res;
	},


	/**
	 * Convert a javascript language object to a JSON string
	 * 
	 * @param Object object The object that we want to convert
	 * 
	 * @returns string
	 */
	objectToJson : function(object){

		return JSON.stringify(object);
	},


	/**
	 * Reads a received javscript object and stores all values to the corresponding properties on the provided class instance
	 * 
	 * @param object The object that we want to convert
	 * @param class The class instance that will be filled with the source data
	 * 
	 * @returns string
	 */
	objectToClass : function(obj, classInstance){

		var props = Object.getOwnPropertyNames(obj);

		for(var i = 0; i < props.length; i++){

			if(classInstance.hasOwnProperty(props[i])){

				if(Object.prototype.toString.call(obj[props[i]]) === '[object Array]'){

					// TODO: part en la que la propietat es un array

				}else{

					if(typeof (classInstance[props[i]]) != "function"){

						classInstance[props[i]] = obj[props[i]];
					}
				}
			}
		}

		return classInstance;
	},


	/**
	 * Tries to convert a received object to its respective class name.
	 * 
	 * @param Object obj The object that we want to convert
	 * 
	 * @returns string The classname for the received object, or 'Object' if classname could not be detected. Note that it is not 100% accurate.
	 */
	objectToClassName : function(obj){

		// TODO: això no és gens fiable. a vera si existeix alguna forma de detectar la classe de un objecte de forma fiable

		var className = 'Object';

		if(typeof obj === obj.valueOf()){

			className = obj.valueOf();
		}

		if(typeof object === obj.constructor.name){

			className = obj.constructor.name;
		}

		return className;
	},


	/**
	 * Serialize an object with key -> value pairs to a string encoded as an URL with GET parameters. 
	 * Example: an array {"a": 1, "b": 2, "c": 3} is converted to the following string: a=1&b=2&c=3
	 * 
	 * @param object The obj to be serialized
	 * 
	 * @returns string The encoded string
	 */
	objectToGetParameters : function(obj){

		var result = "";

		var props = Object.getOwnPropertyNames(obj);

		for(var i = 0; i < props.length; i++){

			result += "&" + encodeURIComponent(props[i]) + "=" + encodeURIComponent(obj[props[i]]);
		}

		return result.substring(1, result.length);
	},


	/**
	 * Convert a received xml structure to a javascript object.
	 * 
	 * @param mixed xml An xml string or jquery xml document that will be converted to the javascript object
	 * 
	 * @returns object The resulting serialized object
	 */
	xmlToObject : function(xml){

		if(typeof xml == 'string' || xml instanceof String){

			var xmlString = xml;

			xml = $($.parseXML(xmlString).documentElement);
		}

		var result = {};

		$.each(xml[0].attributes, function(i, attrib){

			result[attrib.name] = attrib.value;
		});

		// Loop the xml attributes and fill the object with them
		xml.children().each(function(){

			result[$(this)[0].nodeName] = SerializationUtils.xmlToObject($(this));
		});

		return result;
	},


	/**
	 * Convert a received xml structure to a plain string
	 * 
	 * @param object xml A jquery xml document to convert
	 * 
	 * @returns String a plain string containing the converted xml
	 */
	xmlToString : function(xml){

		// Modern browsers
		if(typeof XMLSerializer !== 'undefined'){

			return (new XMLSerializer()).serializeToString(xml[0]);
		}

		// IE
		if(xml[0].xml){

			return xml[0].xml;
		}

		throw new Error("SerializationUtils.xmlToString - Invalid xml document received or no XmlSerializer available");
	}


};