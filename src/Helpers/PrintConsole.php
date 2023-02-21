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
}