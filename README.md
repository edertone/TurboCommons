### General purpose cross-language development library
Devs around the world do the same things every day with different languages. They perform string modifications, validations, data conversions, file operations and more.

Why must they learn new ways of doing the same thing every time they move to a new language?

Reading a file, validating an email address, counting the words on a string, parsing a csv ... Why not create a cross language global library that does it exactly the same way on all languages? A library that has the same class and function names, the same variables and delivers exactly the same behaviour?

### Introducing Turbo Commons!
TurboCommons tries to standarize all those common operations so they are performed the same way across all possible languages. There's a set of well known common problems, with a set of well known common solutions that are the same for all the programming languages in the world. Our goal is that any developer who knows this library can start doing the same things with Php, typescript, javascript, Java, C# ... without having to learn new method and class names. In brief, make our coding lifes easier!

### What does it do?
This library tries to cover the following development areas:
- Data manipulation: Strings, Arrays, Objects, HashMaps, XML, CSV, ...
- Full standard ISO 8601 date/time values and timezone manipulation
- Universal conversion and serialization / de-serialization of complex data structures
- Pictures manipulation and transformation
- Localization management (text translations) 
- Global exception management
- Validation: Mails, Urls, postal codes, ...
- Remote operations: Sending emails, http requests, ftp operations ...

### How many languages does it support

- Php
- Typescript
- Javascript 3
- Javascript 5
- Javascript 6

We want to increase this list. So! if you want to translate the library to your language of choice, please contact us and help us build the most standard cross language development library in the world! We're starting so we need your help to port this library to as many languages as possible, and more important, we need to code the SAME unit tests across all the implemented languages. This is the only way to guarantee that TurboCommons delivers exactly the same behaviour everywhere.

### Install

- Php:
```
Currently only available via .Phar download. (Composer package coming soon)
require 'TurboCommons-Php/TurboCommons-Php-X.X.XX.phar';
use org\turbocommons\src\main\php\utils\StringUtils;
$n = StringUtils::countWords("word1 word2 word3");
```
- Typescript:
```
npm install turbocommons-ts
import { StringUtils } from 'turbocommons-ts';
let n = StringUtils.countWords("word1 word2 word3");
```
- Javascript 3:
```
npm install turbocommons-es3
<script src="turbocommons-es3/TurboCommons-ES3-X.X.X.js"></script>
var StringUtils = org_turbocommons.StringUtils;
var n = StringUtils.countWords("word1 word2 word3");
```
- Javascript 5:
```
npm install turbocommons-es5
<script src="turbocommons-es5/TurboCommons-ES5-X.X.X.js"></script>
var StringUtils = org_turbocommons.StringUtils;
var n = StringUtils.countWords("word1 word2 word3");
```
- Javascript 6:
```
npm install turbocommons-es6
<script src="turbocommons-es6/TurboCommons-ES6-X.X.X.js"></script>
var StringUtils = org_turbocommons.StringUtils;
var n = StringUtils.countWords("word1 word2 word3");
```

### Download
If you don't want to use a package manager like npm or composer, latest compiled versions can be found at:

- Typescript: Coming soon
- Javascript: http://turbocommons.org/resources/shared/zip/TurboCommons-JS5.zip
- Php: http://turbocommons.org/resources/shared/zip/TurboCommons-Php.zip

### Dependencies
The main goal for this library is to have zero dependencies. We are building a true standalone general purpose library.

### Documentation
- Typescript / Javascript: http://turbocommons.org/resources/shared/html/doc-ts
- Php: http://turbocommons.org/resources/shared/html/doc-php/namespaces/org.html

### Official website
The official site http://turbocommons.org is under development. Here you'll find examples, tutorials, and tons of documentation for all the library classes and methods. Stay tuned!

### Implementation details
If we want to create a library that does this magic, we must be really strict. There are some rules that must be followed to make sure our goal is reached:

- TurboCommons specification is language agnostic, but it is aimed to work basically with an OOP methodology.

- Classes, methods and utilities must be classified in a way that is as easy to understand as possible.

- A class, method or utility that is defined on the library must solve a generic problem that can be implemented on any programming language (OOP style).

- Any function that is defined on the library must give exactly the same result for a given set of parameters, no matter in which language is coded. This is really important, and to make sure this is as true as possible, we code the **same exact unit tests for all the classes and methods on all the translated languages**.

- All methods on the library must be translated from one language to another, have the same name, and try to keep the same code structure when possible.

- As said before, but to make it clear: Any utility, class or method that is part of this library must give the **same results on the same exact unit tests for all the implemented languages**.

### Does it really work?
We are 100% active users of the library, and we can tell you it really helps! Nowadays, everybody works with more than one language on an average development project. This library helps a lot with keeping a consistent project code base.

- Example 1: You can serialize an object or class in php, and de-serialize it again in JS or Typescript, to get exactly the same structure. Cross language serialization? YEAH! Just a small taste of all the things that can be done when your code works the same on all your different project layers.
- Example 2: You can use the library HashMap object in Php and TS with exactly the same methods and behaviour.
- Example 3: You can use ValidationManger to validate a form on your front end, and use it again on the back end with the guarantee that validation will work exactly the same on both sides.

This is just a small taste of what you can achieve when you have a cross language library.

**Learn once, code forever**

### Support
Turbo Commons is 100% free and open source, but we will be really pleased to receive any help, support, comments or donations to help us improve this library. If you like it, spread the word!

[![Donate](http://turbocommons.org/resources/shared/images/DonateButton.png)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=53MJ6SY66WZZ2&lc=ES&item_name=TurboCommons&no_note=0&cn=A%c3%b1adir%20instrucciones%20especiales%20para%20el%20vendedor%3a&no_shipping=2&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)

TurboCommons is part of the [turboframework.org](http://turboframework.org) project.
