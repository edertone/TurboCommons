### General purpose cross-language development library
Devs around the world do the same things every day with different languages. They perform string modifications, validations, data conversions, file operations and more.

¿Why must they learn new ways of doing the same thing every time they move to a new language?

Reading a file, validating an email address, counting the words in a string... we do it more or less the same way on all languages. So why not create a global library that does it exactly the same way on all languages? A library that has the same class and function names, the same variables, the same namespaces and delivers exactly the same behaviour on all languages?

### Introducing Turbo Commons!
TurboCommons tries to standarize all those common operations so they are performed the same way across all possible languages. There's a set of well known common problems, with a set of well known common solutions that are the same for all the programming languages in the world. Our goal is that any developer who knows this library can start doing the same things with JS, Java or C# without having to learn something new. In brief, make our coding lifes easier!

### What does it do?
This library tries to cover the following application areas:
- Data structures manipulation: Strings, Arrays, Objects, XML, CSV, Dates, ...
- Conversion and serialization / de-serialization of complex data structures
- Pictures manipulation and transformation
- Cross language localization (languages translation) management
- Global exception management
- Complex cross language validation: Mails, Urls, postal codes, ...
- Sending emails, operations with ftp and http, ...

### How we do it?
If we want to create a library that does this magic, we must be really strict. There are some rules that must be followed to make sure our goal is reached:

- TurboCommons specification is language agnostic, but it is aimed to work basically with an OOP methodology.

- Classes, methods and utilities must be classified in a way that is as easy to understand as possible.

- A class, method or utility that is defined on the library must solve a generic problem that can be implemented on any programming language (OOP style).

- Any function that is defined on the library must give exactly the same result for a given set of parameters, no matter in which language is coded. This is really important, and to make sure this is as true as possible, we code the same exact unit tests for all the classes and methods on all the translated languages.

- All methods on the library must be translated from one language to another, and try to keep the same structure when possible.

- As said before, but to make it clear: Any class or method that is coded in any language for this library must give the exact same results on the same exact unit tests.

### How many languages does it support	 
Currently, TurboCommons is written in php and javascript (and an early pre-alpha java version too), but we want to port it to as many languages as possible.

So! if you want to translate the library to your language of choice, please contact us and help us build the most standard cross language development library in the world! We're starting so we need your help to port this library to as many languages as possible, and more important, we need to code the SAME unit tests across all the implemented languages.

### Does it really work?
We are 100% active users of the library, and we can tell you it really helps! Nowadays, everybody works with more than one language on an average development project. This library helps a lot with keeping a consistent project code base.

For example: You can serialize an object or class in php, and de-serialize it again in JS, to get exactly the same information. Cross language serialization? YEAH! Just a small taste of all the things that can be done when your code works the same on all your different project layers.

Learn once, code forever

### Documentation
- Javascript: http://turbocommons.org/resources/shared/html/doc-js
- Php: http://turbocommons.org/resources/shared/html/doc-php/namespaces/org.html

### Download
Latest version can be downloaded at:

- Javascript: http://turbocommons.org/resources/shared/zip/TurboCommons-JS-0.3.805.zip

- Php: http://turbocommons.org/resources/shared/zip/TurboCommons-Php-0.3.1036.zip

### Dependencies
The main goal for this library is to have zero dependencies, but sometimes this is not totally possible. So currently, the javascript version requires jquery 1.8.3 for some of the features. Php version is totally dependency free now.

### Coming soon
The official site http://turbocommons.org is under development. Here you'll find examples, tutorials, and tons of documentation for all the library classes and methods. Stay tuned!

### Support
Turbo Commons is 100% free and open source. But we will be really pleased to receive any help, support, comments or donations to help us improve this library. If you like it, spread the word!

[![Donate](http://turbocommons.org/resources/shared/images/DonateButton.png)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=53MJ6SY66WZZ2&lc=ES&item_name=TurboCommons&no_note=0&cn=A%c3%b1adir%20instrucciones%20especiales%20para%20el%20vendedor%3a&no_shipping=2&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)
