<?php
namespace ThiagoMeloo\TerminalDebug\Helpers;
use function Termwind\{render};

class PrintConsole {

    /**
     * Variants of print
     * 
     *@var array
     */
    const VARIANTS = [
        'server' => '[SERVER]:',
        'client' => '[CLIENT]:',
        'debug' => '[DEBUG]:',
        'default' => '[]:',
    ];


    /**
     * Colors of print
     * 
     * @var array
     */
    const COLORS = [
        'success' => 'bg-green-600',
        'error' => 'bg-red-600',
        'warning' => 'bg-yellow-600',
        'info' => 'bg-blue-600',
        'default' => 'bg-white',
    ];

    private $text = null;
    private $type = null;
    private $variant = null;


    /**
     * Create a new object print.
     * 
     * @param string $text 
     * @param array $options (type => (success | error | warning | info | null), variant => (server | client | debug | null)) 
     */
    public function __construct(string $text, array $options = []){

        $type = $options['type'] ?? 'default';

        $this->text = $text;
        $this->type = $type;
        $this->variant = self::VARIANTS[$options['variant']] ?? self::VARIANTS['default'];

        return $this;

    }

    
    /**
     * Create a new object print variant server.
     * 
     * @param string $text
     * @param array $options (type => (success | error | warning | info | null))
     */
    public static function server(){
        $args = func_get_args();
        $args[1]['variant'] = 'server';
        return new self(...$args);
    }

    /**
     * Create a new object print variant client.
     * 
     * @param string $text
     * @param array $options (type => (success | error | warning | info | null))
     */
    public static function client(){
        $args = func_get_args();
        $args[1]['variant'] = 'client';
        return new self(...$args);
    }

    /**
     * Create a new object print variant debug.
     * 
     * @param string $text
     * @param array $options (type => (success | error | warning | info | null))
     */
    public static function debug(){
        $args = func_get_args();
        $args[1]['variant'] = 'debug';
        return new self(...$args);
    }

    /**
     * Print text default.
     */
    public function default(){
        $colorClass = self::COLORS['default'];
        $this->print($colorClass);
    }

    /**
     * Print text success.
     */
    public function success(){
        $colorClass = self::COLORS['success'];
        $this->print($colorClass);
    }   

    /**
     * Print text error.
     */
    public function error(){
        $colorClass = self::COLORS['error'];
        $this->print($colorClass);
    }
    
    /**
     * Print text warning.
     */
    public function warning(){
        $colorClass = self::COLORS['warning'];
        $this->print($colorClass);
    }

    /**
     * Print text info.
     */
    public function info(){
        $colorClass = self::COLORS['info'];
        $this->print($colorClass);
    }


    /**
     * Struct print text.
     * @param string $colorClass class color of background variant
     */
    protected function print(string $colorClass){

        render(
            <<<HTML
                <div class="my-1">
                    <div class="px-1 $colorClass">
                        {$this->variant}
                    </div>
                    <em class="ml-1">
                        {$this->text}
                    </em>
                </div>
        HTML);
        
    }


    /**
     * Print text json.
     */
    public function json(){
        //set class by type
        $colorClass = self::COLORS[$this->type] ?? self::COLORS['default'];

        //trait object to pretty json
        $textObj = PrettyJson::parse($this->text);

        render(
            <<<HTML
                <div class="my-1 w-full flex $colorClass">
                    <div class="pt-1 w-full">
                        {$this->variant}
                    </div>
                    <hr>
                    <div class="w-full">
                        <pre class="flex-1">
                            {$textObj}
                        </pre>
                    </div>
                </div>
        HTML);
    }

    /**
     * Print text helper info comands.
     */
    public static function help(){

        //get version in composer.json
        $composer = json_decode(file_get_contents(__DIR__ . '/../../composer.json'), true);
        $version =   'v'.$composer['version'] ?? 'v0.0.0';

        render(<<<HTML
            <table >
                <thead title="Terminal Debug" class="bg-blue text-white px-10"></thead>
                <tbody>
                        <tr>
                            <th>Execute server</th>
                            <td>-s</td>
                            <td>--server</td>
                            <td>execute to server mod to await message clients.</td>
                        </tr>
                        <tr>
                            <th>Execute client</th>
                            <td>-c</td>
                            <td>--client</td>
                            <td>execute to client mod to send message to server.</td>
                        </tr>
                        <tr>
                            <th>Add message</th>
                            <td>-m "message"</td>
                            <td>--message "message"</td>
                            <td>set message from client or server response.</td>
                        </tr>
                        <tr>
                            <th>Help</th>
                            <td>-h</td>
                            <td>--help</td>
                            <td>show help.</td>
                        </tr>
                </tbody>
                <tfoot title="$version" class="bg-blue text-white px-10 "></tfoot>
            </table>
        HTML);
        

    }
}