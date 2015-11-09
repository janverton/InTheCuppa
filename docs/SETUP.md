# ITC API Application setup

## .ini Files:

[Linux environment is assumed]

The following .ini files are expected to be available

- /etc/itc/itc.ini (Not used yet, is there for cdn config etc)
- /etc/itc/mysql.ini (Needs an example)

## Virtual host

[apache is assumed]

Use the included virtual host example and add it to your apache
configuration. Replace both of these defaults for your own:

- api.yourdomain.com
- /your/document/root/

## Database

[MySQL is assumed]

Execute the db.sql file to create a default empty InTheCuppa API