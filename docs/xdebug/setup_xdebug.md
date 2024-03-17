---
title: Setting up Xdebug on PhpStorm
subtitle: Using Docker as the server
description: Step-by-step guide to configure Xdebug on PhpStorm with Docker as the server on a Linux system.
status: draft
tags:
    - PhpStorm
    - Xdebug
    - Docker
author: Your Name
---

# Setting up Xdebug on PhpStorm

---

## Description
This documentation provides a comprehensive guide on configuring Xdebug in PhpStorm with Docker as the server. It is tailored for users operating on a Linux system.

## Context
Configuring Xdebug is essential for efficient debugging of PHP code.

By integrating it with PhpStorm and Docker, developers can streamline the debugging process, ensuring a smoother and more effective development workflow.

## Prerequisites
Before you proceed, ensure the following prerequisites are met:

- [PhpStorm](https://www.jetbrains.com/fr-fr/phpstorm/) is installed on your system.
- [Docker](https://www.docker.com/) is installed and running.
- The Docker Compose file related to your project is available.

## Documentation

### Step 1: Opening PhpStorm and Navigating to Settings
Open PhpStorm. Navigate to File > Settings to access the settings page. 
> You can use shortcut keys ++ctrl+alt+s++

??? note "Screenshot"
    ![Docker Server](images/Configuration_de_XDEBUG_1.png)
    ![Docker Server](images/Configuration_de_XDEBUG_2.png)

### Step 2: Adding a New PHP Interpreter
In the settings, go to `Languages & Frameworks` > `PHP`. Click the `+` icon at the top-right corner to add a new CLI interpreter.

??? note "Screenshot"
    ![Docker Server](images/Configuration_de_XDEBUG_3.png)
    ![Docker Server](images/Configuration_de_XDEBUG_4.png)
    ![Docker Server](images/Configuration_de_XDEBUG_5.png)
    ![Docker Server](images/Configuration_de_XDEBUG_6.png)
    ![Docker Server](images/Configuration_de_XDEBUG_7.png)
    ![Docker Server](images/Configuration_de_XDEBUG_8.png)
    ![Docker Server](images/Configuration_de_XDEBUG_9.png)
    ![Docker Server](images/Configuration_de_XDEBUG_10.png)
    ![Docker Server](images/Configuration_de_XDEBUG_11.png)
    ![Docker Server](images/Configuration_de_XDEBUG_12.png)
    ![Docker Server](images/Configuration_de_XDEBUG_13.png)
    ![Docker Server](images/Configuration_de_XDEBUG_14.png)

### Step 3: Align PhpStorm and Xdebug Ports
Check that PhpStorm listens to the same port as Xdebug in your Docker container. Typically, ports are in the range of `9000-9005`.

??? note "Screenshot"
    ![Docker Server](images/Configuration_de_XDEBUG_15.png)
    ![Docker Server](images/Configuration_de_XDEBUG_16.png)
    ![Docker Server](images/Configuration_de_XDEBUG_17.png)

### Step 4: Install Xdebug Extension on Browser and Initiate Debugging
Install the Xdebug extension on your browser. Activate the extension, then click on the debug icon to begin debugging.

You can find debugging extensions here: [Xdebug Browser Extensions](https://www.jetbrains.com/help/phpstorm/browser-debugging-extensions.html)

??? note "Screenshot"
    ![Docker Server](images/Configuration_de_XDEBUG_18.png)
    ![Docker Server](images/Configuration_de_XDEBUG_19.png)

### Step 5: Listening to Debugging and Setting Breakpoints
Start listening to the debugging process. Choose a line of code for debugging, set a breakpoint in PhpStorm. Refresh the browser page. PhpStorm will display a pop-up to select the server for debugging. Click on "accept."

??? note "Screenshot"
    ![Docker Server](images/Configuration_de_XDEBUG_20.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_21.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_22.png) 

### Mapping The App Folder or Root
PhpStorm maps the public folder by default. Depending on your project structure, manually add the mapping for the 'app' folder or the root directory.

??? note "Screenshot"
    ![Docker Server](images/Configuration_de_XDEBUG_23.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_24.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_25.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_26.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_27.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_28.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_29.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_30.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_31.png) 
    ![Docker Server](images/Configuration_de_XDEBUG_32.png) 


## Conclusion
By following these steps, you have successfully configured Xdebug on PhpStorm with Docker as the server. This integration enhances your ability to debug PHP code effectively.

## References
- [PhpStorm Documentation](https://www.jetbrains.com/help/phpstorm/configuring-xdebug.html)
- [Xdebug Documentation](https://xdebug.org/docs/)