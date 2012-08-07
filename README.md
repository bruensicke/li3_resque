# li3_resque

lithium library for processing and scheduling background jobs using php-resque

## early alpha - non-functional - work-in-progress

thanks for stopping by. I am in the process of defining how this library works. If you want to join forces or need something like this, feel free to contact me.

## fork notes
* This is the Knodes version of li3_resque. It deviated from d1rk's (great) efforts into a hackish solution that simply works but isn't as well structured.
* This fork provides a static ResqueProxy class that channels all methods either a Resque class (php-resque), a ResqueScheduler (php-resque-scheduler)

## installation
add the repo as a submodule, the make sure to update submodules recursively, e.g.:

    git submodule add https://github.com/Knodes/li3_resque.git libraries/li3_resque
    git submodule update --init --recursive