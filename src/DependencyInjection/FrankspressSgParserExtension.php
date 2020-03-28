<?php


namespace Frankspress\SgParserBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FrankspressSgParserExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new XmlFileLoader($container, new FileLocator( __DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);

        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('sg_parser_bundle.api_parser');
        $definition->setArgument(6, $config['attachment']['handle_attachment']);

        $definition = $container->getDefinition('sg_parser_bundle.upload_handler');
        $definition->setArgument(2, $config['attachment']['php_injection']);
        $definition->setArgument(3, $config['attachment']['file_upload_size']);
        $definition->setArgument(4, $config['attachment']['mime_types']);

        $definition = $container->getDefinition('sg_parser_bundle.email_handler');
        $definition->setArgument(2, $config['email']['max_body_length']);
        $definition->setArgument(3, $config['email']['raw_response']);
        $definition->setArgument(4, $config['email']['raw_subject']);


    }
}