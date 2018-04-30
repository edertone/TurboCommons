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

8 - Make sure the git tag is updated with the new project version that's been published to npm

9 - Get the downloadable zip files for each target and update the files inside with the new versions
	- docs, readme, compiled code, etc...
	
10 - Upload the new zip versions to turbocommons website for direct download

11 - Upload the new generated docs to the turbocommons website
	- review that links to docs still work
