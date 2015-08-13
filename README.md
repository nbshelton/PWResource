# PWResource
Provides various tools and utilities for players of Perfect World International.

These features include:
- A *Database* module for browsing/searching in-game items
- A *Calculator* module for creating, displaying, and sharing character builds
- A *Roster* module for creating player rosters, organizing them into squads, and exporting this data into text for use elsewhere

## Installation / Setup

PWResource was created to run on on Apache 2.4 with PHP 5.5 and MySQL 5.6.

Apache's home directory should be set to the public/ folder.
The config/database.ini config file should be filled out with your database's information.

Test data needed for a functional system is provided in db_init/. This will create the "elementdata" database that PWResource uses as well as the "pwr" database, which is used to store Roster information.


## About / Purpose

PWResource was initially conceived to fill the void left by the disappearance of Skyflox's pwcalc.com website (which later returned, but has seen no updates since). Its intended features have since grown and its purpose altered to more of a personal interest and learning experience. 

PWResource uses a custom built-from-scratch routing system both to avoid the unnecessary overhead of features that wouldn't be used and, more importantly, so I could learn how routing systems actually functioned. 

The Roster Builder module of PWResource also utilizes WebSockets (via Ratchet) to enable inter-client collaboration without polling the server for updates. 


PWResource is far from finished, but my hope remains to eventually publish it in its entirety so PWI players may make use of it. 