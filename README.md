CSP MySQL User Manager
======================

A set of PHP-scripts to manage users in CardserverProxy

## Requirements
* Apache or IIS with PHP support (or any other webserver that support PHP)
* MySQL (version 5 or later)
* PHP (version 5 or later)
* CardserverProxy (version 0.8.13 or later)

## Installation
1. Upload all the files to your web server.
2. Browse to the install-dir to start the installation, http://your.cmum.url/install/
3. Download the generated config.php and place in the root of you cmum dir
4. Remove the install dir from the cmum dir
5. All done!
6. Setup csp to fetch users from cmum, described bellow

CSP config example for use on a local webserver:
<user-manager class="com.bowman.cardserv.XmlUserManager" allow-on-failure="false" log-failures="true">
<auth-config>
<update-interval>5</update-interval>
<user-source name="genxmloutput">
<user-file-url>http://some.webserver/genxml/genxml.php</user-file-url>
</user-source>
</auth-config>
</user-manager>

CSP config example for use on a remote (or local) secure webserver:
<user-manager class="com.bowman.cardserv.XmlUserManager" allow-on-failure="false" log-failures="true">
<auth-config>
<update-interval>5</update-interval>
<user-source name="genxmloutput">
<user-file-url>http://user:password@some.safe.webserver/genxml/genxml.php</user-file-url>
</user-source>
</auth-config>
</user-manager>

## Secretkey
The secretkey is used for securing the sessions when browsing cmum, but also when encrypting the admin passwords. So if you move to another server or take a backup of your cmum installation, make sure to include the config.php. Or at least write down the secretkey, without it you won't be able to login to cmum with the admin account in the database.

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
