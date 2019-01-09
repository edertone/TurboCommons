# How to make the library available to the public:

1 - Commit and push all the new version changes to repository.

2 - Review git changelog to decide the new version value based on the GIT changes: minor, major, ...

3 - Make sure the git tag is updated with the new project version we want to publish
    (Either in git local and remote repos)

4 - Generate a release build executing tests (tb -crt)

5 - Create a folder on your desktop and add all files from dist/NNN/

6 - Copy the package.json file from this folder to each of the dist/NNN/ created folders

7 - Add the readme.md file if exists to each of the dist/NNN/ folders

8 - Update the version number on the package.json file

9 - Update the project name on the package.json file:
    turbocommons-es5
    turbocommons-es6
    turbocommons-ts

10 - Open a command line inside each package.json folder and run:
    npm publish

11 - Verify that new versions appear for all the packages at www.npmjs.com/~edertone

12 - Get the downloadable zip files for each target and update the files inside with the new versions
    - docs, readme, compiled code, etc..

13 - Upload the new zip versions to turbocommons website for direct download
    - review that zip download work as expected

14 - Upload the new generated docs to the turbocommons website
    - review that links to docs still work
