PrestaShop-MadModules
=====================

Price File is a simple module that exports info from your products into a csv file.<br>
Currently it's only beeing developed for my site and clients.<br>

Current security key on minor: 652696073734

ToDo
======
- @ install, copy a file to import.
- Create a preset called Mod_PriceFile or something.
- Customer updates desired values to match import.
- Figuer out how to run Import controll from module.
- User SERVER cfg to get [SERVER]/modules/pricefile/pricefile_[SERVER_HASH].csv
- Due to html bug in import, escape @ export " to \"

Changelog
=====================
```
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
