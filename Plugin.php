<?php namespace Tasaduqh\AzureStorage;

use Backend;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use Illuminate\Filesystem\FilesystemAdapter;
use Cms\Classes\Controller;
use Twig\TwigFunction;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'AzureStorage',
            'description' => 'Azure Blob Storage Adapter for October CMS',
            'author' => 'Tasaduq H',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        // $this->app['config']->set('tasaduq.azurestorage::config', include __DIR__ . '/config/azure.php');
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {

          Storage::extend('azure', function ($app, $config) {
            $connectionString = sprintf(
                'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s;BlobEndpoint=%s',
                $config['name'],
                $config['key'],
                $config['endpoint']
            );

            $client = BlobRestProxy::createBlobService($connectionString);
            $adapter = new AzureBlobStorageAdapter($client, $config['container']);
            $filesystem = new Filesystem($adapter);

            return new FilesystemAdapter($filesystem, $adapter, $config);
        });

           // Define the azureStorageUrl singleton
           \App::singleton('azureStorageUrl', function() {
            return function($path) {
                $container = config('filesystems.disks.azure.container');
                $endpoint = config('filesystems.disks.azure.endpoint');
                return "{$endpoint}/{$container}/{$path}";
            };
        });

        // Adding the Twig function
        \Event::listen('cms.page.beforeDisplay', function($controller, $url, $page) {
            $twig = $controller->getTwig();
            $function = new TwigFunction('azureStorageUrl', function ($path) {
                $azureStorageUrl = app('azureStorageUrl');
                return $azureStorageUrl($path);
            });
            $twig->addFunction($function);
        });
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Tasaduq\AzureStorage\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'tasaduq.azurestorage.some_permission' => [
                'tab' => 'AzureStorage',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'azurestorage' => [
                'label' => 'AzureStorage',
                'url' => Backend::url('tasaduq/azurestorage/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['tasaduq.azurestorage.*'],
                'order' => 500,
            ],
        ];
    }
}
