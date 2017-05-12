/**
 * {RcmApiLibApiData}
 * @constructor
 */
var RcmApiLibApiData = function () {
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

/**
 * Class RcmApiLibApiData
 */
angular.module('rcmApiLib').factory(
    'rcmApiLibApiData',
    function ($http, $log) {

        /**
         * Class RcmApiLibApiData
         */
        return RcmApiLibApiData
    }
);
