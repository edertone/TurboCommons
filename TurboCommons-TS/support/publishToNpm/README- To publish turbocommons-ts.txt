To publish the library to npm:



1 - Generate a release build
2 - Create a folder on your desktop and add all files from dist/ES5/umd
3 - Create a package.json at the root of the created desktop folder
4 - Add the following code inside the package.json file (updating the correct version number):

{
  "name": "turbocommons-ts",
  "version": "0.0.0",
  "description": "General purpose typescript library that implements frequently used and generic software development tasks",
  "main": "index.js",
  "types": "index.d.ts",
  "scripts": {
    "test": "test.js"
  },
  "repository": {
    "type": "git",
    "url": "https://github.com/edertone/TurboCommons"
  },
  "keywords": [
    "turbo",
    "commons",
    "typescript"
  ],
  "author": "edertone",
  "license": "Apache-2.0",
  "bugs": {
    "url": "https://github.com/edertone/TurboCommons/issues"
  },
  "homepage": "turbocommons.org"
}


5 - Open a command line inside the created desktop folder and run : npm publish