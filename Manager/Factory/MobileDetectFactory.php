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
use Sfynx\BrowserBundle\Manager\Lib\MobileDetect;

/**
 * MobileDetect Factory
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
class MobileDetectFactory
{
    /**
     * @var MobileDetect
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
     */
    public function __construct(Browscap $client, RequestStack $request) {
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
        if ($this->request->attributes->has('sfynx-mobiledetect')) {
            return $this->request->attributes->get('sfynx-mobiledetect');
        }
        $this->request->attributes->set('sfynx-mobiledetect', $this->client);

        return $this->client;
    }
}
