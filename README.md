PrestaShop-MadModules
=====================

InfoFile is a simple module that exports info from your products into a csv file.<br>
Currently it's only beeing developed for my site and clients.<br>

Current security key on minor: 652696073734

ToDo
======
InfoFile

- Include, New, Exclude list in import config
	- Do this for export to, there might be some products we don't want to export
- Re-Add combinations to pricefile
- Client also need to set if a certain field should be imported at a diffrent field (i.e price_without_tax as gross_price), needs 2 option. Clone to, Import as
	- Example is the only vaild option. Use a simple switch Import price_without_tax as gross_price YES / NO
	- Re-thinked, that is default behevaior.
- Client need to be able to choose what fields should be imported
- Save the Include, Exclude list.

Changelog
=====================
```

Version 0.1
-----------


-- InfoFile --


Version 0.10
-----------
[+] Added option syntax
[*] Rewrote move html
[+] Added extended move settings
[+] Added JavaScript to extended form
[*] Corrected install


Version 0.9
------------
[+] Added divs/select to export config
[+] Added code to add products to export tabel
[-] Fixed incorrect submit_action

Version 0.8
------------
[-] Fixed install code (Thanks to El Patron's code)
[*] Renamed some functions
[*] class folder renamed to classes
[*] Norm on csv class
[*] Renamed DB tabels
[+] Added export mysql
[/] Partially added export settings
[/] Partially added form override
[*] Fixed some spelling / code

Version 0.7
------------
[*] Updated description
[*] Finished update of Class csv
[+] Added cookie button
[+] Added ID to exported fields
[/] Partially added mysql tabels to install (Not working!)
[*] Splitted Settings to seperate fields

Version 0.6.1
------------
[*] Added enclose to csv class

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
[/] Partially added install / config code

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
