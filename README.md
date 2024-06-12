
# Azure Filesystem Driver

This plugin adds a Filesystem driver for Azure Blob Storage.

You need to install the league/flysystem-azure-blob-storage package to use this driver.

    composer require league/flysystem-azure-blob-storage:^3.0   

Using the Azure driver

Simply add another disk in filesystems.php.

You will need your Azure Blob account name, API key and container name.

Update the filesystems.php config file

    'disks' => [
        'media' => [
            'driver'    => 'azure',
            'endpoint'  => env('AZURE_ENDPOINT'),
            'name'      => env('AZURE_STORAGE_NAME'),
            'key'       => env('AZURE_STORAGE_KEY'),
            'container' => env('AZURE_STORAGE_CONTAINER'),
            'url'       => env('AZURE_STORAGE_URL'),  // Optional
            'visibility' => 'public',
            'throw' => false
        ],

        'azure' => [
            'driver' => 'azure',
            'endpoint'  => env('AZURE_ENDPOINT'),
            'name'      => env('AZURE_STORAGE_NAME'),
            'key'       => env('AZURE_STORAGE_KEY'),
            'container' => env('AZURE_STORAGE_CONTAINER'),
            'url'       => env('AZURE_STORAGE_URL'),  // Optional
            'visibility' => 'public',
            'throw' => false
        ],
    ]
You can create as many disks as you want.

Add this to your .env file

    AZURE_STORAGE_NAME=your_account_name
    AZURE_STORAGE_KEY=your_account_key
    AZURE_STORAGE_ENDPOINT=https://your_account_name.blob.core. windows.net
    AZURE_STORAGE_CONTAINER=your_container_name
    
Add the following to your .env file to tell your setup to use Azure disk

    FILESYSTEM_DRIVER=azure


Now you can start uploading your files using the Media section of October CMS.

To access the files you may use the following code

    azureStorageUrl('image.jpg')

For example

    <img src="{{ azureStorageUrl('image.jpg') }}" alt="Image from Azure Blob Storage">
