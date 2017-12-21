<?php
/**
 * This file is part of the <Browser> project.
 *
 * @category   Browser
 * @package    DependencyInjection
 * @subpackage Configuration
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
namespace Sfynx\BrowserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @category   Browser
 * @package    DependencyInjection
 * @subpackage Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sfynx_browser');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        $this->addCookiesConfig($rootNode);
        $this->addMailConfig($rootNode);

        return $treeBuilder;
    }

    /**
     * Cookies config
     *
     * @param ArrayNodeDefinition $rootNode
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addCookiesConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
        ->children()
            ->arrayNode('cookies')
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('application_id')->cannotBeEmpty()->end()
                    ->booleanNode('date_expire')->defaultValue(true)->end()
                    ->scalarNode('date_interval')->defaultValue("PT4H")->end()
                ->end()
            ->end()
        ->end();
    }

    /**
     * Mail config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addMailConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()                
            ->arrayNode('browscap')
            ->isRequired()
                ->children()
                    ->scalarNode('cache_dir')->defaultValue(null)->end()
                    ->booleanNode('silent')->defaultValue(false)->end()
                    ->scalarNode('timeout')->defaultValue(10000)->end()
                    ->scalarNode('update_interval')->defaultValue(432000)->end()
                    ->scalarNode('error_interval')->defaultValue(7200)->end()
                ->end()
            ->end()                    
    	->end();
    }
}
