<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;

/**
 * This class is copied in every Composer installed project and available to all
 *
 * To require it's presence, you can require `composer-runtime-api ^2.0`
 */
class InstalledVersions
{
    private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => 'f7d3621d3aa46ff9b44f5d8e5d62d48fb4b897a1',
    'name' => '__root__',
  ),
  'versions' => 
  array (
    '__root__' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => 'f7d3621d3aa46ff9b44f5d8e5d62d48fb4b897a1',
    ),
    'composer/installers' => 
    array (
      'pretty_version' => 'v1.11.0',
      'version' => '1.11.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ae03311f45dfe194412081526be2e003960df74b',
    ),
    'justinrainbow/json-schema' => 
    array (
      'pretty_version' => '5.2.10',
      'version' => '5.2.10.0',
      'aliases' => 
      array (
      ),
      'reference' => '2ba9c8c862ecd5510ed16c6340aa9f6eadb4f31b',
    ),
    'pronamic/wp-datetime' => 
    array (
      'pretty_version' => '1.2.1',
      'version' => '1.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e55a883a8166fa6ae06d886e917f5c91abfcce39',
    ),
    'pronamic/wp-gravityforms-nl' => 
    array (
      'pretty_version' => '3.0.1',
      'version' => '3.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '3b561651e2e0e68912d1d777a45928724cdecb6e',
    ),
    'pronamic/wp-money' => 
    array (
      'pretty_version' => '1.2.6',
      'version' => '1.2.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '2e83eccbca13475ce3b11d3e354c06b66da896c6',
    ),
    'razorpay/razorpay' => 
    array (
      'pretty_version' => '2.6.1',
      'version' => '2.6.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '2d736a50c991440ae129b846a319b2172a17c404',
    ),
    'rmccue/requests' => 
    array (
      'pretty_version' => 'v1.8.0',
      'version' => '1.8.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'afbe4790e4def03581c4a0963a1e8aa01f6030f1',
    ),
    'roundcube/plugin-installer' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'shama/baton' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'stripe/stripe-php' => 
    array (
      'pretty_version' => 'v7.77.0',
      'version' => '7.77.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6724447481f6fb8c2e714165e092adad9ca470a',
    ),
    'viison/address-splitter' => 
    array (
      'pretty_version' => '0.3.4',
      'version' => '0.3.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ebad709276aaadce94a3a1fe2507aa38a467a94a',
    ),
    'wp-pay-extensions/charitable' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.2.1',
      ),
      'reference' => '4b0d1ef4cbbae54e1089fc8379981fae80036ca8',
    ),
    'wp-pay-extensions/easy-digital-downloads' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.1.4',
      ),
      'reference' => '3a9870be16dcf3693022cb947a93da157c12d8de',
    ),
    'wp-pay-extensions/formidable-forms' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.2.1',
        1 => '9999999-dev',
      ),
      'reference' => 'ea2f20a5c3a1379441b98c1aa8b74c7730e84df3',
    ),
    'wp-pay-extensions/give' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.2.0',
      ),
      'reference' => 'dd2542aa04ff6ce273c66a7eaca5664a2b34ac30',
    ),
    'wp-pay-extensions/gravityforms' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.5.2',
      ),
      'reference' => 'f8b093178aaeb6bdcbfd604c59ca8bfff2ed1cfd',
    ),
    'wp-pay-extensions/memberpress' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.2.3',
      ),
      'reference' => '5c8cb0e432ffdcfe6292b21b9f42178d94bc2605',
    ),
    'wp-pay-extensions/ninjaforms' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '1.4.0',
      ),
      'reference' => 'f487d9cacc6f323e030856a7d408b906f6912e6f',
    ),
    'wp-pay-extensions/restrict-content-pro' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.3.1',
      ),
      'reference' => 'e035ea5bde6647fb82c2d1da7f3154cda8354d82',
    ),
    'wp-pay-extensions/woocommerce' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.2.1',
      ),
      'reference' => '873fb1993145a7a2d0e0a9f0e5c619f45e8f8df4',
    ),
    'wp-pay-gateways/mollie' => 
    array (
      'pretty_version' => '2.2.2',
      'version' => '2.2.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '9023e98e821f172c10b1956910de35397b6429b5',
    ),
    'wp-pay/core' => 
    array (
      'pretty_version' => 'dev-knitpay-master',
      'version' => 'dev-knitpay-master',
      'aliases' => 
      array (
        0 => '2.6.2',
      ),
      'reference' => 'e35bb664ce98d5996f3f81e1a934f2f9d9e45aef',
    ),
  ),
);
    private static $canGetVendors;
    private static $installedByVendor = array();

    /**
     * Returns a list of all package names which are present, either by being installed, replaced or provided
     *
     * @return string[]
     * @psalm-return list<string>
     */
    public static function getInstalledPackages()
    {
        $packages = array();
        foreach (self::getInstalled() as $installed) {
            $packages[] = array_keys($installed['versions']);
        }


        if (1 === \count($packages)) {
            return $packages[0];
        }

        return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
    }

    /**
     * Checks whether the given package is installed
     *
     * This also returns true if the package name is provided or replaced by another package
     *
     * @param  string $packageName
     * @return bool
     */
    public static function isInstalled($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (isset($installed['versions'][$packageName])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the given package satisfies a version constraint
     *
     * e.g. If you want to know whether version 2.3+ of package foo/bar is installed, you would call:
     *
     *   Composer\InstalledVersions::satisfies(new VersionParser, 'foo/bar', '^2.3')
     *
     * @param VersionParser $parser      Install composer/semver to have access to this class and functionality
     * @param string        $packageName
     * @param string|null   $constraint  A version constraint to check for, if you pass one you have to make sure composer/semver is required by your package
     *
     * @return bool
     */
    public static function satisfies(VersionParser $parser, $packageName, $constraint)
    {
        $constraint = $parser->parseConstraints($constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));

        return $provided->matches($constraint);
    }

    /**
     * Returns a version constraint representing all the range(s) which are installed for a given package
     *
     * It is easier to use this via isInstalled() with the $constraint argument if you need to check
     * whether a given version of a package is installed, and not just whether it exists
     *
     * @param  string $packageName
     * @return string Version constraint usable with composer/semver
     */
    public static function getVersionRanges($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            $ranges = array();
            if (isset($installed['versions'][$packageName]['pretty_version'])) {
                $ranges[] = $installed['versions'][$packageName]['pretty_version'];
            }
            if (array_key_exists('aliases', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
            }
            if (array_key_exists('replaced', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
            }
            if (array_key_exists('provided', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
            }

            return implode(' || ', $ranges);
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getPrettyVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['pretty_version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['pretty_version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as reference
     */
    public static function getReference($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['reference'])) {
                return null;
            }

            return $installed['versions'][$packageName]['reference'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @return array
     * @psalm-return array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}
     */
    public static function getRootPackage()
    {
        $installed = self::getInstalled();

        return $installed[0]['root'];
    }

    /**
     * Returns the raw installed.php data for custom implementations
     *
     * @return array[]
     * @psalm-return array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>}
     */
    public static function getRawData()
    {
        return self::$installed;
    }

    /**
     * Lets you reload the static array from another file
     *
     * This is only useful for complex integrations in which a project needs to use
     * this class but then also needs to execute another project's autoloader in process,
     * and wants to ensure both projects have access to their version of installed.php.
     *
     * A typical case would be PHPUnit, where it would need to make sure it reads all
     * the data it needs from this class, then call reload() with
     * `require $CWD/vendor/composer/installed.php` (or similar) as input to make sure
     * the project in which it runs can then also use this class safely, without
     * interference between PHPUnit's dependencies and the project's dependencies.
     *
     * @param  array[] $data A vendor/composer/installed.php data set
     * @return void
     *
     * @psalm-param array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>} $data
     */
    public static function reload($data)
    {
        self::$installed = $data;
        self::$installedByVendor = array();
    }

    /**
     * @return array[]
     */
    private static function getInstalled()
    {
        if (null === self::$canGetVendors) {
            self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
        }

        $installed = array();

        if (self::$canGetVendors) {
            // @phpstan-ignore-next-line
            foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
                if (isset(self::$installedByVendor[$vendorDir])) {
                    $installed[] = self::$installedByVendor[$vendorDir];
                } elseif (is_file($vendorDir.'/composer/installed.php')) {
                    $installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
                }
            }
        }

        $installed[] = self::$installed;

        return $installed;
    }
}
