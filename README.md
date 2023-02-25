# TERMINAL DEBUG

![Example](./examples/img/example1.png)

----------

**Terminal Debug** was born with the purpose of debugging values ​​by printing them directly to the terminal, thus keeping the dev's focus on their code most of the time.

The tool is still in its early stages, so all your suggestions are welcome.


### Requirements
- PHP ^8.0
    - extension socket active  

### Install
- In terminal execute: 

    ```
        composer require thiagomeloo/terminal-debug --dev
    ```
### Usage
- Start the server to listen for debug messages with the following command:

    ```
        php vendor/bin/terminal-debug -s
    ```

- Alternatively you can run the client via the command line:
    ```
        php vendor/bin/terminal-debug -c "terminal debug example"
    ```

### Example
-  To send debug message to the server you have the following options:
    
    - Print string message:
        ```php
        <?php
            tDebug("Terminal Debug");
        ```
    - Print array
        ```php
        <?php
            tDebug([1,2,3, 4]);
        ```
    
    - Print object
        ```php
        <?php
            $obj = (object)[
                "example" => "terminal debug"
            ];
            tDebug($obj);
        ```
### Prints
- Start Server

    ![startServer](./examples/img/exampleStartServer.png)

- Receive Debug

    ![exampleDebug](./examples/img/exampleDebug.png)

- Command Line Helpers

    ![comandHelpers](./examples/img/exampleCommandHelp.png)

    

