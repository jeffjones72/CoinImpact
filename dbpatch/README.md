1. cd bin

2. run dbpatch setup - to create dbpatch.ini file
run ./dbpatch.php (linux) or dbpatch.bat (windows)

./dbpatch.php status

3. edt dbpatch.ini:
db.params.username = "user"
db.params.password = "pass"
db.params.dbname = "db"

4. create empty table db_changelog
run ./dbpatch.php (linux) or dbpatch.bat (windows)

./dbpatch.php status

5. run manual ignore.sql 

6. update to latest patch

./dbpatch.php update


[![Build Status](https://secure.travis-ci.org/dbpatch/DbPatch.png?branch=master)](http://travis-ci.org/dbpatch/DbPatch)

DbPatch
======

DbPatch is a commandline utility to manage/track (my)sql/php patch files.

Requirements
------------
DbPatch requires the following:

* PHP 5.1.x or higher
* MySQL (client)

Documentation
-------------
For more detailed information you can check our online documentation at: [https://github.com/dbpatch/DbPatch/wiki](https://github.com/dbpatch/DbPatch/wiki)

License
-------
DbPatch is BSD licensed, see LICENSE.

Support
-------
If you're looking for help, you can reach me by:

*  Twitter: @dbpatchproject ([http://twitter.com/dbpatchproject](http://twitter.com/dbpatchproject))
*  Github: [https://github.com/dbpatch/DbPatch](https://github.com/dbpatch/DbPatch)


