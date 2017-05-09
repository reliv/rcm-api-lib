/**
 * Class ApiParams
 */
angular.module('rcmApiLib')
    .factory(
        'rcmApiLibApiParams',
        function ($http, $log) {

            /**
             * Class ApiParams
             */
            return function () {

                var self = this;

                /**
                 * Default Error callback
                 * @param {object} data
                 * @param status
                 * @param headers
                 * @param config
                 */
                var defaultError = function (data, status, headers, config) {
                };

                /**
                 * Default reject for Promise
                 * @param error
                 */
                var defaultReject = function (error) {
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
                 * triggerSuccess
                 * @param data
                 * @param status
                 * @param headers
                 * @param config
                 */
                self.triggerError = function (data, status, headers, config) {
                    self.error(data, status, headers, config);

                    // @bc If the error function has been changed and reject has not change,
                    // then we handle the promise with a resolve
                    // With the assumption that the promise was not used
                    // Because un-caught promises throw errors
                    if (self.error !== defaultError && self.reject === defaultReject) {
                        self.resolve(data, status, headers, config);
                        return;
                    }

                    self.reject(data, status, headers, config);
                };

                /**
                 * Success callback, called if http and API is successful
                 * @param {object} data
                 * @param status
                 * @param headers
                 * @param config
                 */
                self.success = function (data, status, headers, config) {
                };

                /**
                 * Error callback, called if http or API is fails
                 * @param {object} data
                 * @param status
                 * @param headers
                 * @param config
                 */
                self.error = defaultError;

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
        }
    );
