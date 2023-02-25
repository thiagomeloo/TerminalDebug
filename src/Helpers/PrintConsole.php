<?php

namespace ThiagoMeloo\TerminalDebug\Helpers;

use function Termwind\{render};

/**
 * Class helper to print message in terminal debug.
 * 
 * @package ThiagoMeloo\TerminalDebug\Helpers
 */
class PrintConsole
{
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

    /**
     *  Content of print
     * 
     * @var string
     */
    private $content = null;

    /**
     * Type of print
     * 
     * @var string
     */
    private $type = null;

    /**
     * Variant of print
     * 
     * @var string
     */
    private $variant = null;


    /**
     * Create a new object print.
     * 
     * @param string $content 
     * @param array $options (type => (success | error | warning | info | null), variant => (server | client | debug | null)) 
     */
    public function __construct(string $content, array $options = [])
    {
        $type = $options['type'] ?? 'default';

        $this->content = $content;
        $this->type = $type;
        $this->variant = self::VARIANTS[$options['variant']] ?? self::VARIANTS['default'];

        return $this;
    }


    /**
     * Create a new object print variant server.
     * 
     * @param string $content
     * @param array $options (type => (success | error | warning | info | null))
     */
    public static function server(): self
    {
        $args = func_get_args();
        $args[1]['variant'] = 'server';
        return new self(...$args);
    }

    /**
     * Create a new object print variant client.
     * 
     * @param string $content
     * @param array $options (type => (success | error | warning | info | null))
     */
    public static function client(): self
    {
        $args = func_get_args();
        $args[1]['variant'] = 'client';
        return new self(...$args);
    }

    /**
     * Create a new object print variant debug.
     * 
     * @param string $content
     * @param array $options (type => (success | error | warning | info | null))
     */
    public static function debug(): self
    {
        $args = func_get_args();
        $args[1]['variant'] = 'debug';
        return new self(...$args);
    }

    /**
     * Print text default.
     */
    public function default(): void
    {
        $colorClass = self::COLORS['default'];
        $this->printMessage($colorClass);
    }

    /**
     * Print text success.
     */
    public function success(): void
    {
        $colorClass = self::COLORS['success'];
        $this->printMessage($colorClass);
    }

    /**
     * Print text error.
     */
    public function error(): void
    {
        $colorClass = self::COLORS['error'];
        $this->printMessage($colorClass);
    }

    /**
     * Print text warning.
     */
    public function warning(): void
    {
        $colorClass = self::COLORS['warning'];
        $this->printMessage($colorClass);
    }

    /**
     * Print text info.
     */
    public function info(): void
    {
        $colorClass = self::COLORS['info'];
        $this->printMessage($colorClass);
    }


    /**
     * Print message by colorClass.
     * @param string $colorClass - color of variant background.
     */
    protected function printMessage(string $colorClass): void
    {
        render(
            <<<HTML
                <div class="my-1">
                    <div class="px-1 $colorClass">
                        {$this->variant}
                    </div>
                    <em class="ml-1">
                        {$this->content}
                    </em>
                </div>
        HTML
        );
    }

    /**
     * Content header debug.
     */
    protected function headerDebug(): string
    {
        $dataHora = date('d-m-Y H:i:s');
        return <<<HTML
            <div class="w-full px-1 pt-1 justify-between">
                <div class="flex-1">{$this->variant}</div>
                <em class="flex-1 font-bold ">Terminal Debug</em>
                    <em class="flex-1">
                        {$dataHora}
                    </em>
            </div>
            <hr class="text-slate-300">
        HTML;
    }

    /**
     * Print text json.
     */
    public function json(): void
    {
        //set class by type
        $colorClass = self::COLORS[$this->type] ?? self::COLORS['default'];

        //trait object to pretty json
        $textJson = PrettyJson::isJson($this->content) ? PrettyJson::parse($this->content) : $this->content;

        render(
            <<<HTML
                <div class="w-auto flex flex-1 $colorClass mt-1 mb-1">
                    {$this->headerDebug()}
                    <pre class="block w-full ml-1">
                        {$textJson}
                    </pre>
                </div>
        HTML
        );
    }

    /**
     * Print text object.
     */
    public function object(): void
    {
        //set class by type
        $colorClass = self::COLORS[$this->type] ?? self::COLORS['default'];

        $textObj = PHP_EOL . $this->content;

        render(
            <<<HTML
                <div class="w-auto flex flex-1 $colorClass mt-1 mb-1">
                    {$this->headerDebug()}
                    <div class="px-2">
                        <div class="flex-1">
                            {$this->colorizeDump($textObj)}
                        </div>
                    </div>
                </div>
        HTML
        );
    }

    /**
     * Colorize var_dump.
     * @param string $code - var_dump code.
     * @return string - var_dump code colorized.
     */
    protected function colorizeDump($code)
    {
        //replace all break lines to html tags to colorize
        $code = str_replace(PHP_EOL, '<br>', $code);
        $code = str_replace(' ', '&nbsp;', $code);

        //colorize string
        $code = preg_replace('/(\"\w+\"|\w+\'\')/', '<span class="text-green-500">$1</span>', $code);

        //colorize int
        $code = preg_replace('/(\:\s|\:|\=\>\s|\=\>)(\d+)/', '$1<span class="text-orange-600">$2</span>', $code);

        // colorize var_dump type
        $code = preg_replace('/(int|float|string|bool|array|object|resource|NULL|mixed|callable|iterable|void|false|true|null|self|parent|static)/', '<span class="text-red-500">$1</span>', $code);

        //colorize var_dump private|protected|public
        $code = preg_replace('/(private|protected|public)/', '<span class="text-pink-600">$1</span>', $code);

        //colorize (){}[]
        $code = preg_replace('/(\(|\)|\{|\}|\[|\])/', '<span class="text-blue-600">$1</span>', $code);

        return  $code;
    }

    /**
     * Print text helper info comands.
     */
    public static function help(): void
    {

        //get version in composer.json
        $composer = json_decode(file_get_contents(__DIR__ . '/../../composer.json'), true);
        $version =   'v' . $composer['version'] ?? 'v0.0.0';

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
