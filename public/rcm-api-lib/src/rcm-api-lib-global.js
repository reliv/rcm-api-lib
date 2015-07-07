/**
 * Exposes Angular service to global scope for use by other libraries
 * - This is to support jQuery and native JavaScript modules and code
 */
var rcmApiLib = {
    rcmApiLibService: null // defined using angular
};

/**
 * Angular injector to get rcmApiLib Module services
 */
angular.injector(['ng', 'rcmApiLib']).invoke(
    [
        'rcmApiLibService',
        function (rcmApiLibService) {
            rcmApiLib.rcmApiLibService = rcmApiLibService;
        }
    ]
);
