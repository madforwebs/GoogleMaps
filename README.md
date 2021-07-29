CalendarBundle
=============

The `GoogleMapsBundle` means easy-to-implement and feature-rich maps in your Symfony application!

## Installation

### Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require madforwebs/google-maps-bundle
```
This command requires you to have Composer installed globally, as explained
in the `installation chapter` of the Composer documentation.

### Enable the Bundle


Then, enable the bundle by adding the following line in the ``app/AppKernel.php``
file of your project:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new MadForWebs\GoogleMapsBundle\GoogleMapsBundle(),
        );

        // ...
    }

    // ...
}
```
