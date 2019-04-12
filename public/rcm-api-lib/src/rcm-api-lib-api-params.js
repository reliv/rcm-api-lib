/**
 * {RcmApiLibApiParams}
 * @constructor
 */
var RcmApiLibApiParams = function () {

    var self = this;

    /**
     *
     */
    var defaultCallback = function () {
    };

    /**
     * URL of request (can contain parsable params in format {myParam})
     * @type {string}
     */
    self.url = '';

    /**
     * URL Params that will replace parsable params in url
     * @type {object}
     */
    self.urlParams = null;

    /**
     * POST PUT DELETE data
     * @type {object}
     */
    self.data = null;

    /**
     * GET query params
     * @type {object}
     */
    self.params = null;

    /**
     * Expected response type
     * - if the response data is not of these types
     *   the response will be considered an error
     */
    self.responseTypes = ['object', 'array', 'null'];

    /**
     * Loading callback, used to track loading state
     * @param {boolean} loading
     */
    self.loading = function (loading) {
    };

    /**
     * triggerSuccess
     * @param data
     * @param status
     * @param headers
     * @param config
     */
    self.triggerSuccess = function (data, status, headers, config) {
        self.success(data, status, headers, config);
        self.resolve(data, status, headers, config);
    };

    /**
     * triggerError
     * @param data
     * @param status
     * @param headers
     * @param config
     */
    self.triggerError = function (data, status, headers, config) {
        self.error(data, status, headers, config);
        self.reject(data, status, headers, config);
    };

    /**
     * Success callback, called if http and API is successful
     * @param {object} data
     * @param status
     * @param headers
     * @param config
     */
    self.success = defaultCallback;

    /**
     * Error callback, called if http or API is fails
     * @param {object} data
     * @param status
     * @param headers
     * @param config
     */
    self.error = defaultCallback;

    /**
     * Promise success callback, called if http and API is successful
     * @param {object} data
     * @param status
     * @param headers
     * @param config
     */
    self.resolve = function (data, status, headers, config) {
    };

    /**
     * Promise error callback, called if http or API is fails
     * @param error
     */
    self.reject = function (error) {
    };

    /**
     * populate
     * @param apiParams
     */
    self.populate = function (apiParams) {
        var value;
        for (var key in apiParams) {
            if (!apiParams.hasOwnProperty(key)) {
                continue;
            }

            // Ignore these on populate
            if (key == 'triggerSuccess' || key == 'triggerError') {
                continue;
            }

            value = apiParams[key];

            if (typeof self[key] === 'function' && typeof value === 'function') {
                self[key] = value;
            }
            if (typeof self[key] !== 'function' && typeof value !== 'function') {
                self[key] = value;
            }
        }
    }
};

/**
 * Class RcmApiLibApiParams
 */
angular.module('rcmApiLib').factory(
    'rcmApiLibApiParams',
    ['$http', '$log',
        function ($http, $log) {

            /**
             * Class RcmApiLibApiParams
             */
            return RcmApiLibApiParams;
        }
    ]
);
