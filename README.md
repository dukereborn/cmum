CSP MySQL User Manager
======================

A set of PHP-scripts to manage users in CardserverProxy (CSP)

## Requirements
* Web server with PHP support (such as Apache, IIS)
* MySQL 5.0 or newer
* PHP 5.3.0 or newer, with session support
* A web browser with cookies and javascript enabled
* CardserverProxy (version 0.8.13 or later)

## Download
You can download the newest release at http://github.com/dukereborn/cmum/releases/

If you prefer to follow the git repository, the following branch and tag names may be of interest
* ``master`` is the current stable release
* ``trunk`` is the development branch

## Installation
1. Upload all the files to your web server
2. Browse to the install-dir to start the installation, http://your.cmum.url/install/
3. Follow the installation steps
4. Download the generated config.php and place in the root of you cmum-dir
5. Remove the install-dir and upgrade-dir from the cmum-dir
6. Setup csp to fetch users from cmum, described bellow

CSP config example for use on a local webserver
```
<user-manager class="com.bowman.cardserv.XmlUserManager" allow-on-failure="false" log-failures="true">
  <auth-config>
  <update-interval>5</update-interval>
  <user-source name="genxmloutput">
    <user-file-url>http://some.webserver/genxml.php</user-file-url>
  </user-source>
  </auth-config>
</user-manager>
```

CSP config example for use on a remote (or local) secure webserver
```
<user-manager class="com.bowman.cardserv.XmlUserManager" allow-on-failure="false" log-failures="true">
  <auth-config>
  <update-interval>5</update-interval>
  <user-source name="genxmloutput">
    <user-file-url>http://user:password@some.safe.webserver/genxml.php</user-file-url>
  </user-source>
  </auth-config>
</user-manager>
```

## Upgrading from version 3.x
1. Delete all files (except config.php) from your cmum-dir
2. Upload the new files into your cmum-dir
3. Open cmum in your browser and you will get a notice to upgrade your installation
4. Remove the upgrade-dir and install-dir from your cmum dir

## Upgrading from version 2.x
Due to a complete rework of the whole system and database, a complete reinstallation is required if you want to upgrade from version 2.x to 3.x.

1. Export your users to a csv file using the csv export tool in cmum
2. Follow the installation steps above (note! make sure you install cmum3 into a new clean database)
3. Import your users using the csv import tool in cmum

## Secretkey
The secretkey is used for securing the sessions when browsing cmum, but also when encrypting the admin passwords. So if you move to another server or take a backup of your cmum installation, make sure to include the config.php. Or at least write down the secretkey, without it you won't be able to login to cmum with the admin account in the database.

## Genxml options
These options were added to manipulate the output from genxml to fit special builds of csp, or by any other reason that you would like to exclude some data in the genxml output. The usage is just like when using genxml key ```genxml.php?option=options``` to use it together with genxml key it should look like this ```genxml.php?key=genxmlkey&amp;option=options``` multiple options are separated by semicolon ```;``` like this ```genxml.php?key=genxmlkey&amp;option=option1;option2;option3```

**IMPORTANT!!!** Remember to use ```&amp;``` and not just ```&``` between key and option if using both.

Below you will find a list of available options for genxml
```
nousername
nopassword
nodisplayname
noipmask
noprofiles
nomaxconnections
noadmin
noenabled
nomapexclude
nodebug
noemail
nostartdate
noexpiredate
nocustomvalues
```

The options simply does what they are called, for example nostartdate will exclude the start-date="dd-mm-yyyy" from the genxml output.

## Trouble using cmum with the latest csp version?
This is because some changes where made to the httpd server inside csp. But there is a easy fix for that. Open your csp config-file and check under the ```<status-web>``` section. If there is a ```<ghttpd>``` section, change it to the one bellow, and if there is none, just add these lines. It's the ```<ghttpd enabled="false">``` that is the important part.
```
<ghttp enabled="false">
  <alternate-port>8083</alternate-port>
  <log-file>log/ghttp-access.log</log-file>
  <feeder-password>secret1</feeder-password>
  <open-access-password>secret2</open-access-password>
</ghttp>
```

Full config example for the above part, so you place it correctly
```
<rmi>
  <display-name>PROXY</display-name>
  <status-web>
    <listen-port>12345</listen-port>
    <ssl enabled="true"> 
      <keystore password="secretpassword">etc/csp_keystore</keystore>
    </ssl>
    <war-file>lib/cs-status.war</war-file>
    <super-users>admin</super-users>
    <ghttp enabled="false">
      <alternate-port>8083</alternate-port>
      <log-file>log/ghttp-access.log</log-file>
      <feeder-password>secret1</feeder-password>
      <open-access-password>secret2</open-access-password>
    </ghttp>
  </status-web>
</rmi>
```

## FAQ - Frequently Asked Questions
**Nothing happens when i start the installation?**
> Check you mysql information and if your user has the correct rights to access the database

**How do I get randomized usernames and password?**
> Double click on the username and/or password field and you will get a random generated username and/or password

**CSP stops fetching users when using start-date?**
> Some versions of csp dont work with start-date. If you need them in cmum, then use genxml options to exclude it from the output to csp.

**Expire-date dosnt work in CSP?**
> Make sure you are using the ```com.bowman.cardserv.AdvXmlUserManager``` user-manager class.

**The installer cant connect to MySQL server?**
> Try using ```localhost``` instead of ```127.0.0.1``` if your MySQL server is installed locally

## Contact me
If you find any bugs, got an idea or just wanna say "Hi!", send me a email on dukereborn@gmail.com. You can also follow me on twitter for updates and news about the development on cmum http://www.twitter.com/dukereborn/

## Donations
If you like cmum and all the work I have put into it, consider donating a buck or two. All donations are welcome through paypal to account dukereborn@gmail.com

## License
Released under the [MIT license](http://makesites.org/licenses/MIT)

CSP MySQL User Manager includes several third party libraries which come under their respective licenses.
