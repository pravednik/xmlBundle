<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Desperado <desperado@minsk-info.ru>
 */
class Configuration implements ConfigurationInterface
{
    /** {@inheritDoc} */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('desperado_xml');
        $rootNode = method_exists(TreeBuilder::class, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('desperado_xml');

        return $treeBuilder;
    }
}
