<?php

return [

    /**
     * An array of any of the configuration profiles that the developer may want.
     *
     * @var array
     */
    'profiles' => [

        /**
         * The default configuration information
         *
         * @var array
         */
        'default' => [

            /**
             * All of the cache settings
             *
             * @var array
             */
            'cache' => [

                /**
                 * How long the cache is maintained in seconds
                 *
                 * @var int
                 */
                'duration' => 3600,

                /**
                 * Whether or not caching is enabled.
                 *
                 * @var boolean
                 */
                'enabled' => true,

                /**
                 * The folder in the "storage"
                 *
                 * @var string
                 */
                'location' => 'rss-feeds',
            ],

            /**
             * Whether or not to force the data feed to be treated as an
             * RSS feed.
             *
             * @var boolean
             */
            'force-feed' => false,

            /**
             * Whether or not the RSS feed should have it's output ordered by date.
             *
             * @var boolean
             */
            'order-by-date' => false,

            /**
             * Whether it should verify SSL, set false to make it work with self-signed certificates
             *
             * @var boolean
             */
            'ssl-verify' => true,
        ],
    ],
];
