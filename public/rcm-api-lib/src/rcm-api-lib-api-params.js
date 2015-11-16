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
                 * Loading callback, used to track loading state
                 * @param {boolean} loading
                 */
                self.loading = function (loading) {
                };
                /**
                 * Success callback, called if http and API is successful (error code == 0)
                 * @param {object} data
                 */
                self.success = function (data) {
                };
                /**
                 * Error callback, called if http or API is fails (error code > 0)
                 * @param data
                 */
                self.error = function (data) {
                };

                /**
                 * populate
                 * @param apiParams
                 */
                self.populate = function (apiParams) {
                    angular.forEach(
                        apiParams,
                        function (value, key) {
                            if(typeof self[key] === 'function' && typeof value === 'function') {
                                self[key] = value;
                            }
                            if(typeof self[key] !== 'function' && typeof value !== 'function') {
                                self[key] = value;
                            }
                        }
                    );
                }
            };
        }
    );
