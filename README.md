# Engine Framework - LAMP Stack

This application is a demo application using the Engine framework from WVULibraries as a base.  This demo application is to teach the LAMP Stack to create web applications for a designer interested in programming aspects associated with the web.

# Setting Up Engine

To appropriately setup Engine we are going to have to create a vagrant box for development and modify the box to represent a Linux server.  We are going to do this in the bootstrap.sh file.  Many of these commands will be bash commands and git commands to setup our dependencies and move files where they need to be.

In this example you are going to need to have VirtualBox and Vagrant installed on your computer.  These same tasks can be run from SSH of a Linux Server running the Centos 6.4 Operating System.

### Centos OS Vagrant Box
```ruby
config.vm.box = "centos6.4"
config.vm.box_url = "https://github.com/2creatives/vagrant-centos/releases/download/v0.1.0/centos64-x86_64-20131030.box"
```

### Linux Dependencies
First we want to declare certain variables that are going to help us to save time in setting up our box.  The variables will be used to determine where our files are stored and what the default root of our public facing file system is going to be.

The GITDIR Variable setups a directory in the tmp folder to hold files.  When the system restarts the tmp directory on a linux system is cleared.  The next gets the latest installs of the Engine Framework from Github and sets a home directory.  The SERVER URL is a setup of your base directory.  The Document root and site root variables setup your public facing directories and link your source.

```bash
GITDIR="/tmp/git"
ENGINEAPIGIT="https://github.com/wvulibraries/engineAPI.git"
ENGINEBRANCH="master"
ENGINEAPIHOME="/home/engineAPI"

SERVERURL="/home/timeTracker"
DOCUMENTROOT="public_html"
SITEROOT="/home/timeTracker/public_html/src"
```

The following code will install apache, mysql, php, git, and some other things that are generally needed for secure web development with the engine framework.

```bash
yum -y install httpd httpd-devel httpd-manual httpd-tools
yum -y install mysql-connector-java mysql-connector-odbc mysql-devel mysql-lib mysql-server
yum -y install mod_auth_kerb mod_auth_mysql mod_authz_ldap mod_evasive mod_perl mod_security mod_ssl mod_wsgi
yum -y install php php-bcmath php-cli php-common php-gd php-ldap php-mbstring php-mcrypt php-mysql php-odbc php-pdo php-pear php-pear-Benchmark
yum -y install emacs emacs-common emacs-nox
yum -y install git
```

Finally we setup apache and the configuration files to point towards our home that we declared in the variables.

```bash
echo "Modifying Apache"
mv /etc/httpd/conf.d/mod_security.conf /etc/httpd/conf.d/mod_security.conf.bak
/etc/init.d/httpd start
chkconfig httpd on

echo "Moving HTTPD Conf Files"
rm -f /etc/httpd/conf/httpd.conf
ln -s /vagrant/serverConfiguration/httpd.conf /etc/httpd/conf/httpd.conf
```

### SERVER Framework Installation
```bash
mkdir -p $GITDIR
cd $GITDIR
git clone -b $ENGINEBRANCH $ENGINEAPIGIT
git clone https://github.com/wvulibraries/engineAPI-Modules.git

mkdir -p $SERVERURL/phpincludes/
ln -s /vagrant/templates $GITDIR/engineAPI/engine/template/
ln -s $GITDIR/engineAPI-Modules/src/modules/* $GITDIR/engineAPI/engine/engineAPI/latest/modules/
ln -s $GITDIR/engineAPI/engine/ $SERVERURL/phpincludes/


mkdir -p $SERVERURL/$DOCUMENTROOT
ln -s /vagrant/src $SITEROOT
ln -s $SERVERURL/phpincludes/engine/engineAPI/latest $SERVERURL/phpincludes/engine/engineAPI/4.0

rm -f $GITDIR/engineAPI/engine/engineAPI/latest/config/defaultPrivate.php
ln -s /vagrant/serverConfiguration/defaultPrivate.php $GITDIR/engineAPI/engine/engineAPI/latest/config/defaultPrivate.php

mkdir -p $SERVERURL/phpincludes/databaseConnectors/
ln -s /vagrant/serverConfiguration/database.lib.wvu.edu.remote.php $SERVERURL/phpincludes/databaseConnectors/database.lib.wvu.edu.remote.php

ln -s $SERVERURL $ENGINEAPIHOME
ln -s $GITDIR/engineAPI/public_html/engineIncludes/ $SERVERURL/$DOCUMENTROOT/engineIncludes

chmod a+rx /etc/httpd/logs -R
sudo ln -s /etc/httpd/logs/error_log /vagrant/serverConfiguration/serverlogs/error_log
sudo ln -s /etc/httpd/logs/access_log /vagrant/serverConfiguration/serverlogs/access_log
```

### PHP Configuration
In the base directory we are going to have to setup an includes folder that adds the engine framework to our document.  We want to do this in a way that we only have to include it once.  The bootstrap is setup to be configured with a specific directory setup for your codebase, but you can always ammend it once you figure out the different layers of items and want to dive in the bash.

![Directory Structure](http://oi64.tinypic.com/2hoyasl.jpg "Directory Structure")

