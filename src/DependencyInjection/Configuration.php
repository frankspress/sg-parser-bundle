<?php


namespace Frankspress\SgParserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {


        $treeBuilder = new TreeBuilder('frankspress_sg_parser');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('email')
                    ->children()
                        ->integerNode('max_body_length')->defaultValue(3000)->info('Int, max body length allowed')->end()
                        ->booleanNode('raw_response')->defaultValue(true)->info('Returns only the actual email or direct response')->end()
                        ->booleanNode('raw_subject')->defaultValue(true)->info('Strips RE: from email subject')->end()
                    ->end()
                ->end()
                ->arrayNode('attachment')
                    ->children()
                        ->booleanNode('handle_attachment')->defaultTrue()->info('Bool, Attachment download is disabled by default')->end()
                        ->scalarNode('file_upload_size')->defaultValue('3M')->info('string, Upload size, 1M, 5M etc., see Symfony File Validator for more info')->end()
                        ->booleanNode('php_injection')->defaultFalse()->info('Bool, Checks for malicious php code')->end()
                        ->arrayNode('mime_types')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}