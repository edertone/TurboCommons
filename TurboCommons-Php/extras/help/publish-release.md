# How to make the library available to the public:

1 - Commit and push all the new version changes to repository.

2 - Review git changelog to decide the new version value based on the GIT changes: minor, major, ...

3 - Make sure the git tag is updated with the new project version we want to publish
    (Either in git local and remote repos)

4 - Generate a release build executing tests (tb -crt)
     - Make sure the phar is generated

5 - For now we are not publishing the library to composer, cause it requires the composer.json file to be on github root
    - so skip composer publishing

6 - Get the downloadable zip file for the library and update the files inside with the new versions
    - docs, readme, compiled code, etc..
    
7 - Upload the new zip version to turbocommons website for direct download
    - review that zip download work as expected

8 - Upload the new generated docs to the turbocommons website
    - review that links to docs still work