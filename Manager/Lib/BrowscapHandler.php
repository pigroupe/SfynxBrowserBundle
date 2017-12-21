<?php

namespace Sfynx\BrowserBundle\Manager\Lib;

use \Exception as BaseException;
use BrowscapPHP\Browscap;
use WurflCache\Adapter\File;

/**
 * Browscap.ini parsing class with caching and update capabilities
 *
 * PHP version 5
 *
 * Copyright (c) 2006-2012 Jonathan Stoppani
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    Browscap
 * @author     Jonathan Stoppani <jonathan@stoppani.name>
 * @author     Vítor Brandão <noisebleed@noiselabs.org>
 * @author     Mikołaj Misiurewicz <quentin389+phpb@gmail.com>
 * @copyright  Copyright (c) 2006-2012 Jonathan Stoppani
 * @version    1.0
 * @license    http://www.opensource.org/licenses/MIT MIT License
 * @link       https://github.com/GaretJax/phpbrowscap/
 */
class BrowscapHandler
{
    /**
     * Options for auto update capabilities
     *
     * $timeout: The timeout for the requests.
     * $updateInterval: The update interval in seconds.
     * $errorInterval: The next update interval in seconds in case of an error.
     *
     * The default source file type is changed from normal to full. The performance difference
     * is MINIMAL, so there is no reason to use the standard file whatsoever. Either go for light,
     * which is blazing fast, or get the full one. (note: light version doesn't work, a fix is on its way)
     */
    public $timeout = 10000;
    public $updateInterval = 432000; // 5 days
    public $errorInterval = 7200; // 2 hours

    /**
     * Flag to enable/disable silent error management.
     * In case of an error during the update process the class returns an empty
     * array/object if the update process can't take place and the browscap.ini
     * file does not exist.
     *
     * @var bool
     */
    public $silent = false;

    /**
     * Path to the cache directory
     *
     * @var string
     */
    public $cacheDir = null;

    /**
     * Constructor class, checks for the existence of (and loads) the cache and
     * if needed updated the definitions
     *
     * @param string $cache_dir
     *
     * @throws Exception
     */
    public function __construct($cache_dir)
    {
        $this->cacheDir = $cache_dir;
    }

    /**
     * XXX parse
     *
     * Gets the information about the browser by User Agent
     *
     * @param string $user_agent   the user agent string
     * @param bool   $return_array whether return an array or an object
     *
     * @throws Exception
     * @return \stdClass|array  the object containing the browsers details. Array if
     *                    $return_array is set to true.
     */
    public function getBrowser($user_agent = null, $return_array = false)
    {
        $this->setCacheDir();

        // we instanciate the cache system from WurflCache
        $bc = new Browscap();
        $wurfl_cache = new File([
            File::DIR => $this->cacheDir,
            'cacheExpiration' => $this->updateInterval
        ]);

        try {
            $browscap_updater = new \BrowscapPHP\BrowscapUpdater();
            $browscap_updater->setCache($wurfl_cache);
            $browscap_updater->setConnectTimeout($this->timeout);
            $browscap_updater->update(\BrowscapPHP\Helper\IniLoader::PHP_INI_FULL);
            $wurfl_cache = $browscap_updater->getCache();
        } catch (\Exception $e) {
//            if (file_exists($ini_file)) {
//                // Adjust the filemtime to the $errorInterval
//                touch($ini_file, time() - $this->updateInterval + $this->errorInterval);
//            } elseif ($this->silent) {
//                // Return an array if silent mode is active and the ini db doesn't exsist
//                return [];
//            }

            if (!$this->silent) {
                throw $e;
            }
        }
        $bc->setCache($wurfl_cache);

        return $bc->getBrowser($this->getUserAgent());
    }

    protected function setCacheDir()
    {
        if (!isset($this->cacheDir)) {
            throw new Exception('You have to provide a path to read/store the browscap cache file');
        }
        // Is the cache dir really the directory or is it directly the file?
        if (substr($this->cacheDir, -4) === '.php') {
            $this->cacheFilename = basename($this->cacheDir);
            $this->cacheDir      = dirname($this->cacheDir);
        }
        $this->cacheDir .= DIRECTORY_SEPARATOR;
    }


    /**
     * Automatically detect the useragent
     *
     * @return string the formatted user agent
     */
    protected function getUserAgent()
    {
        $user_agent = '';
        if (!isset($user_agent) && isset($_SERVER['HTTP_USER_AGENT'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }
        return $user_agent;
    }
}
