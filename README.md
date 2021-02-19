ifengine
========

What ist this?
--------------
Ifengine is an engine and a parser for interactive fiction in PHP.

In the old computing days of the 1970s and 1980s there was a popular game type called text adventures, later named 
interactive fiction. The games were totally text based. The game describes a situation for the player 
(e.g. "You're in a tunnel like hall") and shows an input prompt.

Now the player types in, what he wants to do in a simple natural language sentence like "examine the hall".

The game tries to understand and execute the command. Then it displays the new situation.  


How can i use it?
-----------------
First make sure, that your system meets the requirements:
- A webserver with PHP 7.4 or higher.
- The version control system git must be installed.
- The dependency manager composer must be installed.

First install the project from the git repository. Enter the cli on your webserver, and type:

    git clone git@github.com:dollmetzer/ifengine.git

Then enter the new directory ifengine and type:
    
    composer install

Create the folder ~/sessions and make it writeable for the webserver.
Make the directory ~/public your webroot.

You're done.


How can I expand it's capabilities?
-----------------------------------
... to be written