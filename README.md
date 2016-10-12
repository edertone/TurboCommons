### General purpose cross-language development library
Devs around the world do the same things every day, but with different languages. They perform string modifications, validations, data conversions, file operations... 

So why should they learn new ways of doing the same thing every time they switch?
Wouldn't it be nice to perform the same tasks exactly the same way, no matter the language?

At the end, reading a file, validating an email addres, counting the words in a string... is done the same way on all languages. So why not create a global library that does it exactly the same way on any language? A library that is heavily unit tested to guarantee that it really delivers the promise?

### Introducing Turbo Commons!
 
TurboCommons tries to standarize all those common operations so they are performed the same way across all possible languages. There's a set of well known common problems, with a set of well known common solutions that are the same for all the programming languages in the world. Our goal is that a Php dev who knows this library can start doing the same things with JS, Java or C# without having to learn something new. In brief, make our coding lifes easier!
	
### So... How we do it?
If we want to create a library that does this magic, we must be really strict. There are some rules that must be followed to make sure our goal is reached:

- TurboCommons specification is language agnostic, but it is aimed to work basically with an OOP methodology.

- Classes, methods and utilities must be classified in a way that is as easy to understand as possible.
 
- A class, method or utility that is defined on the library must solve a generic problem that can be implemented on any programming language (OOP style).

- Any function that is defined on the library must give exactly the same result for a given set of parameters, no matter in which language is coded. This is really important, and to make sure this is as true as possible, we code the same exact unit tests for all the classes and methods on all the translated languages.

- All methods on the library must be translated from one language to another, and try to keep the same structure when possible.
	
- As said before, but to make it clear: Any class or method that is coded in any language for this library must give the exact same results on the same exact unit tests.

### How many languages does it support	 
Currently, TurboCommons is written in php and javascript (and an early pre-alpha java version too), but we want to port it to as many languages as possible. 

So! if you want to translate the library to your language of choice, please contact us and help us build the most standard cross language development library in the world! We're starting so we need your help to port this library to as many languages as possible, and more important, we need to code the SAME unit tests across the different languages.

### Does it really help?

We are 100% active users of the library, and we can tell you, it really helps! nowadays, everybody works with more than one language on an average development project. This library helps a lot to keep a consistent project code base.

For example: Isn't it wonderful that you serialize an object or class in php, and de-serialize it again in JS? cross language serialization? YEAH! Just a small taste of all the things that can be done when your code works the same on all your different project layers.

### Current state
The library is currently in an alpha state, so it is possible that lots of things change, but even now it contains lots of useful code and solutions to multiple day to day development tasks. 

For example: do you know that the 'isUrl' method on the library ValidationManager class works exactly the same in JS and PHP? Or that you can call 'removeAccents' on the StringUtils class and have exactly the same result on PHP and JS? Why? because tons of unit tests guarantee it!

### Download
Coming soon, you will be able to download the JS and PHP versions of the library:

- JS: A minified .js file containing all you need to start using the library

- Php: A phar file containing all you need to start using the library

### Coming soon

The official site http://turbocommons.org is under development. Here you'll find examples, tutorials, and tons of documentation for all the library classes and methods. Stay tuned!

### Support or Contact
We will be really pleased to receive any help, support, comments, and donations to help us improve this library.
