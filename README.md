# li3_resque

lithium library for processing and scheduling background jobs using php-resque

## early alpha - non-functional - work-in-progress

thanks for stopping by. I am in the process of defining how this library works. If you want to join forces or need something like this, feel free to contact me.

## Fork notes
* This is the Knodes version of li3_resque. It deviated from d1rk's (great) efforts into a hackish solution that simply works but isn't as well structured.
* This fork provides a static ResqueProxy class that channels all methods either a Resque class (php-resque), a ResqueScheduler (php-resque-scheduler)

## Installation
add the repo as a submodule, the make sure to update submodules recursively, e.g.:

    git submodule add https://github.com/Knodes/li3_resque.git libraries/li3_resque
    git submodule update --init --recursive

### Load the library during the lithium bootstrap process

```php

// add the following to config/bootstrap/libraries.php
Libraries::add('li3_resque');
```

## Usage

### Commands

The plugin provides some additional commands.

To view all commands, run the following

```bash
./li3
```

For more info on each of the commands, prefix --help

```bash
./li3 resque --help
./li3 resque-worker --help
./li3 scheduled-worker --help
``` 

### Using within your application

```php
use li3_resque\extensions\ResqueProxy;

ResqueProxy::enqueue('myqueue','app\jobs\MyJob', array('args' => 'to-pass'));
```

This will queue up a job. The job is located in the `app\jobs`
namespace. Its' class name is MyJob, and an array of args are passed
in. The queue name is `myqueue`.

So now we that we have a job queued up, we need a worker to do the work.

This is invoked using the `resque-worker` command. We must tell it which
queue it is to process.

```bash
./li3 resque-worker --queues=myqueue
```

This will run indefinitely, though you will likely run it using `nohup`.
You can pass additional parameters such as the interval between jobs.
See the help command for more information.
