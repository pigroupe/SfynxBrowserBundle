<?php
/**
 * This file is part of the <Browser> project.
 *
 * @category   Browser
 * @package    Factory
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\BrowserBundle\Manager\Factory;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Sfynx\BrowserBundle\Manager\Lib\BrowscapHandler;

/**
 * Browscap factory
 *
 * @category   Browser
 * @package    Factory
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class BrowscapFactory
{
    /**
     * @var Browscap
     */
    protected $client;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Constructor.
     *
     * @param Browscap $client
     * @param RequestStack $request
     * @param string $cache_dir
     */
    public function __construct(BrowscapHandler $client, RequestStack $request) {
        $this->client = $client;
        $this->request = $request->getCurrentRequest();
    }

    /**
     * Get client
     *
     * @access public
     * @return Browscap
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getClient()
    {
        if ($this->request->cookies->has('sfynx-browser')) {
            return unserialise($this->request->cookies->get('sfynx-browser'));
        }

        return $this->client->getBrowser();
    }
}
