# Engine Framework 4.0 - LAMP Stack

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

![Directory Structure](/Documentation/DirectorySetup.jpg?raw=true "Directory Structure")

# ENGINE FOR DEVELOPMENT
Talking with the developers of Engine, the framework had a few clear goals.
- Security - Specifically protection from injection and penetration attacks.
- Modular builds for rapid development
- Free form development allowing the developer to choose the software design patterns they use.  While Engine itself runs using a Singleton pattern, meaning their can only be one, the apps developed using engine is open for the developer to choose.

This example we are mainly going to talk about MVC.  Many of these same features will work with other examples as well depending on how you want to work with the different features and options.

In our application we have to setup engine to run on our pages and applications.  The way we are going to do this is by creating an engine.php file.

### Engine.php
```php
    // path to my engineAPI install
    require_once '/home/timeTracker/phpincludes/engine/engineAPI/4.0/engine.php';
    $engine = EngineAPI::singleton();

    // Setup Error Rorting
    errorHandle::errorReporting(errorHandle::E_ALL);

    // These are specific to EngineAPI and pulling the appropriate files
    recurseInsert("headerIncludes.php","php");

    // Setup Database Information for Vagrant or eventually the server
    $databaseOptions = array(
        'username' => 'username',
        'password' => 'password',
        'dbName'   => 'timeTracker'
    );

    // makes for easy db commands
    $db  = db::create('mysql', $databaseOptions, 'appDB');

    // Set localVars and engineVars variables
    $localvars  = localvars::getInstance();
    $enginevars = enginevars::getInstance();

    if (EngineAPI::VERSION >= "4.0") {
        $localvars  = localvars::getInstance();
        $localvarsFunction = array($localvars,'set');
    }
    else {
        $localvarsFunction = array("localvars","add");
    }

    // include base variables
    recurseInsert("includes/vars.php","php");

    // load a template to use
    templates::load('timeTemplate');
```

The 2 aspects of the page at the bottom include setting up a template and using local variables to call information and insert php logic into HTML.  This aspect will come in handy later and will be explained in the enxt session.  The important aspect is setting up the error handeling, the engineSingleton, and the database options.

## Simple MVC Style

MVC stand for Model View and Controller.  It is used to develop applications and keep a seperation of concerns and logic.  The model aspect directly deals with the data, logic, and rule of the application.  The View component can be thought of as dealing with logic and what the user sees.  The Controller takes information determines what model and view should be represented.

In our simple MVC we are going to take advantage of a routing system natively built in Engine.  We are going to create our own customer class and a function that will help to manage our routing and views rendering.  We are also going to use a few other things within engine that help to make templating seperation of logic much easier.

### Router

The router class is built into engine, if you want to use it there is a tiny bit of setup required.  The first is to the following htaccess to your main directory.  The current setup allows us to simply have this in the src folder, but depending on your setup you may have to use the command line to place it in your root directory.

**.htaccess**

```bash
<IfModule mod_rewrite.c>
    RewriteEngine On

    ## recursively search parent dir
    # if index.php is not found then
    # forward to the parent directory of current URI
    RewriteCond %{DOCUMENT_ROOT}/$1$2/index.php !-f
    RewriteRule ^(.*?)([^/]+)/[^/]+/?$ /$1$2/ [L]

    # if current index.php is found in parent dir then load it
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{DOCUMENT_ROOT}/$1/index.php -f
    RewriteRule ^(.*?)[^/]+/?$ /$1/index.php [L]
</IfModule>
```

**Router Example**
In order to use the router we must first instantiate the class.  This is done by declaring a router variable and calling an instance function.

```php
// Instantiate the class
$router = router::getInstance();
```

After declaring the class, use the class variable to set callbacks to use for certain routes.  The example below is an example of defining the home route and a callback function.

```php
// syntax
// $router->defineRoute(url, callbackfunction)

// example of syntax for home route and a function called displayHome
$router->defineRoute("/", 'displayRoute');

//after declaring the defineRoute we want to make sure the the router routes to that url
$router->route();
```

The above callback function will be able to take 2 parameters.  These parameters are going to be the URL and any variables declared within the URL.  Think of routes as really just ways to hold information and tell the system what to do next.

```php
    // example of the callback function
    function displayRoute($url, $vars){
        // prints the url as a string
        print "<pre>";
        var_dump($url);
        print "</pre>";

        // prints the variables in the URL as an array
        // for the above example they will be empty.
        print "<pre>";
        var_dump($vars);
        print "</pre>";
    }
```

Here is a more complete example, you can use different callback functions or the same callback functions to determine what you would like to do. This is by no means the limits of what you can do.  Its really just a starting point.  It uses the callbacks to point to each of the views and really control what happens next.

```php
    // Routing
    $router = router::getInstance();
    $router->defineRoute("/", 'displayRoute');
    $router->defineRoute("/{model}", 'displayRoute');
    $router->defineRoute("/{model}/{action}", 'displayRoute');
    $router->defineRoute("/{model}/{action}/{item}", 'displayRoute');
    $router->route();

    // example of the callback function
    function displayRoute($url, $vars){
        // prints the url as a string
        print "<pre>";
        var_dump($url);
        print "</pre>";

        // prints the variables in the URL as an array
        // for the above example they will be empty.
        print "<pre>";
        var_dump($vars);
        print "</pre>";
    }
```

Use the above example, testing the different results typed into the URL's we can see the produced results.

**URL Tests**

**URL:** _"/"_
```php
array(3) {
  ["URI"]=>
  string(1) "/"
  ["count"]=>
  int(1)
  ["items"]=>
  array(1) {
    [0]=>
    array(2) {
      ["path"]=>
      string(0) ""
      ["variable"]=>
      bool(false)
    }
  }
}
```

**URL:** _"/home"_
```php
array(3) {
  ["URI"]=>
  string(5) "/home"
  ["count"]=>
  int(1)
  ["items"]=>
  array(1) {
    [0]=>
    array(2) {
      ["path"]=>
      string(4) "home"
      ["variable"]=>
      bool(false)
    }
  }
}
array(1) {
  ["model"]=>
  string(4) "home"
}
```

**URL:** _"/test/edit"_
```php
array(3) {
  ["URI"]=>
  string(10) "/test/edit"
  ["count"]=>
  int(2)
  ["items"]=>
  array(2) {
    [0]=>
    array(2) {
      ["path"]=>
      string(4) "test"
      ["variable"]=>
      bool(false)
    }
    [1]=>
    array(2) {
      ["path"]=>
      string(4) "edit"
      ["variable"]=>
      bool(false)
    }
  }
}
array(2) {
  ["model"]=>
  string(4) "test"
  ["action"]=>
  string(4) "edit"
}
```

**URL:** _"/test/update/23"_
```php
array(3) {
  ["URI"]=>
  string(15) "/test/update/23"
  ["count"]=>
  int(3)
  ["items"]=>
  array(3) {
    [0]=>
    array(2) {
      ["path"]=>
      string(4) "test"
      ["variable"]=>
      bool(false)
    }
    [1]=>
    array(2) {
      ["path"]=>
      string(6) "update"
      ["variable"]=>
      bool(false)
    }
    [2]=>
    array(2) {
      ["path"]=>
      string(2) "23"
      ["variable"]=>
      bool(false)
    }
  }
}
array(3) {
  ["model"]=>
  string(4) "test"
  ["action"]=>
  string(6) "update"
  ["item"]=>
  string(2) "23"
}
```

The router doesn't prevent us from being able to use directories and custom setups outside of the MVC patterns.  You could very easily add a file that doesn't relate to the other style by developing as a folder and giving it an index.php.

Example would be is if I wanted to create a page about monkeys.  I could create a new folder in my source called monkey and inside that folder have an index.php.  This will get ignored by the router callback and wait for custom php or html to be rendered.

This is also consequently how we use our resources.  If we want to attach some CSS, Images, or JS. we need to place a blank index.php file inside of that folder.  This issue has been noted as an inconvenience and has been added to a debug list, but the features still work perfectly.  Just remember this while working.

# Engine Useful Tools

## Validation

## Form Builder

## MySQL

## GET / POST / SERVER

## Router



## Error Handler

