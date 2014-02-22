PrestaShop-MadModules
=====================

Price File is a simple module that exports info from your products into a csv file.<br>
Currently it's only beeing developed for my site and clients.<br>

Current security key on minor: 652696073734

ToDo
======
- Due to html bug in import, escape @ export " to \"
-	Rewrite to skip enclose, PS should work without them.
- Retink export/import. Clients need to be able to set what products they want.
-	Should de be done on server or client side, and an include list is better, then new products won't be added.
- Export needs to be rewritten. Combinations needs to be exports as such.
-	Problem with that is, that combiantions is imported on to product id.
- Using PrestaShop's import is not enough. Need to create my own. This will simplify all export/import problem.
- Export products with ID's, create a second file with the combinations
- 	Then at client, read include list (Server ID based). Import them, create array for server id's and client id's
-	Then import combinations using that list.
-	Make module save server/id list in table (This will require multiple tabels. One for server/client id's, one for include/exclude/new id's)
-	Use csv class to read file.
- Split Settings to Settings,Import settigns and Export settings
- Add Multie delimter ; and function to split it in to subarray in to csv class
-	When hasHeader is set, use them as key and remove them from final array

Changelog
=====================
```
Version 0.6
-------------
[~] Combination support was removed untill a way to know product ideas is created
[*] Changed csv output
[+] Added a csv class for importer
[*] csv file now converts nl 2 br
[+] Added tmp import button
[+] Printed testdata from csv class

Version 0.5
--------------
[*] Changed csv filename to contain hash
[-] Fixed bad code in _postProcess
[+] Added support for language in config
[*] Hash and run link is only shown if export is enabled

Version 0.4
---------------
[+] Finished and bugfixed install / config
[*] Moved config array to $this->config
[+] Added display of security key and link in config
[*] Cleaned run file
[*] Improved code for config
[~] Removed output for download link, with SERVER settings, it's not need anymore
[+] Added english translation file

Version 0.3
---------------
[*] Only active products are exported
[+] Partily added install / config code

Version 0.2
----------------
[-] Made changelog compatible with github syntax
[+] Added description for module
[*] Updated readme text
[+] Added security string on install
[+] Added security check on run.php

Version 0.1
----------------
[+] Added retrivel of data
[+] Added csv file
[-] Fixed some string bugs
[+] Added support for combos
[*] Improved code
```
