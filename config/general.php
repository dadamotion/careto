<?php
/**
 * Craft 3 Multi-Environment
 * Efficient and flexible multi-environment config for Craft 3 CMS
 *
 * $_ENV constants are loaded by craft3-multi-environment from .env.php via
 * ./web/index.php for web requests, and ./craft for console requests
 *
 * @author    nystudio107
 * @copyright Copyright (c) 2017 nystudio107
 * @link      https://nystudio107.com/
 * @package   craft3-multi-environment
 * @since     1.0.5
 * @license   MIT
 */
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here.
 * You can see a list of the default settings in src/config/GeneralConfig.php
 */
return [
    // All environments
    '*' => [
        // Craft defined config settings
        'defaultSearchTermOptions' => [
            'subLeft' => true,
            'subRight' => true,
        ],
        'enableCsrfProtection' => true,
        'generateTransformsBeforePageLoad' => true,
        'maxUploadFileSize' => 134217728,
        'omitScriptNameInUrls' => true,
        'securityKey' => getenv('SECURITY_KEY'),
        'usePathInfo' => true,
        'useProjectConfigFile' => true,

        // Added JSON for redactor settings & supertable options
        'extraAllowedFileExtensions' => 'json',
        
        // Aliases parsed in sites’ settings, volumes’ settings, and Local volumes’ settings
        'aliases' => [
            '@basePath' => getenv('BASE_PATH'),
            '@baseUrl' => getenv('BASE_URL'),
        ],
        // Custom site-specific config settings
        'custom' => [
            'craftEnv' => CRAFT_ENVIRONMENT,
            'staticAssetsVersion' => 0,
        ]
    ],
    // Live (production) environment
    'production' => [
        // Craft defined config settings
        'allowUpdates' => false,
        'backupOnUpdate' => false,
        'devMode' => false,
        'enableTemplateCaching' => true,
        'isSystemLive' => true,
        'allowAdminChanges'=> false,
        // Aliases parsed in sites’ settings, volumes’ settings, and Local volumes’ settings
        'aliases' => [
            '@analytics' => 'UA-00000000-0',
            '@pixel' => '',
        ],
        // Custom site-specific config settings
        'custom' => [
        ]
    ],
    // Staging (pre-production) environment
    'staging' => [
        // Craft defined config settings
        'allowUpdates' => false,
        'backupOnUpdate' => false,
        'devMode' => false,
        'enableTemplateCaching' => true,
        'isSystemLive' => true,
        'allowAdminChanges'=> false,
        // Aliases parsed in sites’ settings, volumes’ settings, and Local volumes’ settings
        'aliases' => [
        ],
        // Custom site-specific config settings
        'custom' => [
        ]
    ],
    // Local (development) environment
    'local' => [
        // Craft defined config settings
        'allowUpdates' => true,
        'backupOnUpdate' => true,
        'devMode' => true,
        'enableTemplateCaching' => false,
        'isSystemLive' => true,
        // Aliases parsed in sites’ settings, volumes’ settings, and Local volumes’ settings
        'aliases' => [
        ],
        // Custom site-specific config settings
        'custom' => [
        ]
    ],
];