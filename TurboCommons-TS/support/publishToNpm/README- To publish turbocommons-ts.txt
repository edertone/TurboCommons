To publish the library to npm:



1 - Generate a release build

2 - Create a folder on your desktop and add all files from dist/NNN/

3 - Copy the package.json file from this folder to each of the dist/NNN/ created folders

4 - Add the readme.md file if exists to each of the dist/NNN/ folders

5 - Update the version number on the package.json file

6 - Update the project name on the package.json file:
	turbocommons-ts
	turbocommons-es5
	turbocommons-es6

7 - Open a command line inside the created desktop folder and run:
	npm publish

8 - Commit the new build number to the git repository

9 - Generate online html docs:
	- Open a cmd at the root of the project
	- Install typedoc if necessary
	- typedoc --name turbocommons --module commonjs --mode modules --out target/docs/ src/main/ts
	
10 - Upload the new version to turbocommons website for direct download
