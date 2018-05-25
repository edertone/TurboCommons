# How to make the library available to the public:

1 - Make sure the git tag is updated with the new project version we want to publish
	(Either in git local and remote repos)

2 - Generate a release build executing tests (tb -crt)

3 - Create a folder on your desktop and add all files from dist/NNN/

4 - Copy the package.json file from this folder to each of the dist/NNN/ created folders

5 - Add the readme.md file if exists to each of the dist/NNN/ folders

6 - Update the version number on the package.json file

7 - Update the project name on the package.json file:
	turbocommons-es5
	turbocommons-es6
	turbocommons-ts

8 - Open a command line inside the created desktop folder and run:
	npm publish

9 - Get the downloadable zip files for each target and update the files inside with the new versions
	- docs, readme, compiled code, etc..
	
10 - Upload the new zip versions to turbocommons website for direct download
	- review that zip download work as expected

11 - Upload the new generated docs to the turbocommons website
	- review that links to docs still work
