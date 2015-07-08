/**
 * Class ApiData
 */
angular.module('rcmApiLib')
    .factory(
    'rcmApiLibApiData',
    function ($http, $log) {

        /**
         * Class ApiData
         */
        return function () {
            var self = this;
            self.data = null;
            self.messages = [];

            /**
             * getPrimaryMessage
             * @returns {*}
             */
            self.getPrimaryMessage = function () {
                if (self.messages.length > 0) {
                    return self.messages[0];
                }

                return null;
            }
        };
    }
);
