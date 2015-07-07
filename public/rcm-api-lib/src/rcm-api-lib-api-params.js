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
            /**
             * URL of request (can contain parsable params in format {myParam})
             * @type {string}
             */
            this.url = '';
            /**
             * URL Params that will replace parsable params in url
             * @type {object}
             */
            this.urlParams = null;
            /**
             * POST PUT DELETE data
             * @type {object}
             */
            this.data = null;
            /**
             * GET query params
             * @type {object}
             */
            this.params = null;
            /**
             * Loading callback, used to track loading state
             * @param {boolean} loading
             */
            this.loading = function (loading) {
            };
            /**
             * Success callback, called if http and API is successful (error code == 0)
             * @param {object} data
             */
            this.success = function (data) {
            };
            /**
             * Error callback, called if http or API is fails (error code > 0)
             * @param data
             */
            this.error = function (data) {
            };
        };
    }
);
