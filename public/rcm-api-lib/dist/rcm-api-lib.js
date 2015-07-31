/**
 * rcmApiLib Module
 */
angular.module('rcmApiLib', []);

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

/**
 * Class ApiMessage
 */
angular.module('rcmApiLib')
    .factory(
    'rcmApiLibApiMessage',
    function ($http, $log) {

        /**
         * Class ApiMessage
         */
        return function (value) {
            var self = this;
            self.type = 'rcmApiLib';
            self.source = 'client';
            self.code = 'error';
            self.value = 'An unknown error occured while making request';
            self.primary = null;
            self.params = [];
            self.key = '';
            // Client only property
            self.level = 'warning';

            /**
             * buildKey
             */
            self.buildKey = function () {
                self.key = self.type + '.' + self.source + '.' + self.code;
            };

            /**
             * init
             */
            self.init = function (value) {
                self.buildKey();
                if(value) {
                    self.value = value;
                }
            };

            self.init(value);
        };
    }
);

/**
 * General service to wrap standard API JSON returns from rcm-api-lib
 *  - Creates standard return on error if no standard API JSON object received
 *  - Deals with loading state
 *    See: ApiParams.loading
 */
angular.module('rcmApiLib')
    .factory(
    'rcmApiLibService',
    [
        '$http',
        '$log',
        'rcmApiLibApiData',
        'rcmApiLibApiMessage',
        'rcmApiLibApiParams',
        function ($http, $log, rcmApiLibApiData, rcmApiLibApiMessage, rcmApiLibApiParams) {

            var self = this;

            self.config = {
                defaultMessage: 'An unknown error occured while making request'
            };

            /**
             * cache
             * @type {{}}
             */
            self.cache = {};

            /**
             * Class ApiParams
             * @constructor
             */
            self.ApiParams = rcmApiLibApiParams;

            /**
             * Class ApiData - Format expected from server
             * @constructor
             */
            self.ApiData = rcmApiLibApiData;

            /**
             * Class ApiMessage - Format expected for ApiMessages
             * @constructor
             */
            self.ApiMessage = rcmApiLibApiMessage;

            /**
             * GET
             * @param apiParams
             * @param {bool} cache - if you ask for cache it will try to get it from and set it to the cache
             * @returns {*}
             */
            self.getCached = function (apiParams, cacheId) {

                apiParams = self.buildApiParams(apiParams);

                apiParams.loading(true);

                if (!cacheId) {
                    cacheId = apiParams.url;
                }

                self.getCache(
                    cacheId,
                    function (cacheData) {
                        self.apiSuccess(
                            self.cache[apiParams.url],
                            apiParams,
                            'CACHE',
                            null,
                            null
                        );
                    },
                    function () {
                        apiParams.cacheId = cacheId;

                        self.get(
                            apiParams
                        );
                    }
                )
            };

            /**
             * GET
             * @param apiParams
             */
            self.get = function (apiParams) {

                apiParams = self.buildApiParams(apiParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'GET',
                        url: apiParams.url,
                        params: apiParams.params // @todo Validate this works for GET query
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * POST
             * @param apiParams
             */
            self.post = function (apiParams) {

                apiParams = self.buildApiParams(apiParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'POST',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * PATCH
             * @param apiParams
             */
            self.patch = function (apiParams) {

                apiParams = self.buildApiParams(apiParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'PATCH',
                        url: apiParams.url,
                        data: apiParams.data // angular.toJson(data)
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * PUT
             * @param apiParams
             */
            self.put = function (apiParams) {

                apiParams = self.buildApiParams(apiParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'PUT',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * DELETE
             * @param apiParams
             */
            self.del = function (apiParams) {

                apiParams = self.buildApiParams(apiParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'DELETE',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * buildApiParams
             * @param apiParams
             * @returns {*}
             */
            self.buildApiParams = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                return apiParams;
            };

            /**
             * Parse URL string and replace {#} with param value by key
             * @param {string} str
             * @param {array} urlParams
             * @returns {string}
             */
            self.formatUrl = function (str, urlParams) {

                if (typeof urlParams !== 'object' || urlParams === null) {
                    return str;
                }

                for (var arg in urlParams) {
                    str = str.replace(
                        RegExp("\\{" + arg + "\\}", "gi"),
                        urlParams[arg]
                    );
                }

                return str;
            };

            /**
             * setCache
             * @param cacheId
             * @param data
             */
            self.setCache = function (cacheId, data) {
                if (cacheId) {
                    self.cache[cacheId] = angular.copy(data);
                }
            };

            /**
             * getCache
             * @param cacheId
             * @param cacheCallback
             * @param noCacheCallback
             */
            self.getCache = function (cacheId, cacheCallback, noCacheCallback) {

                var cacheData = self.cache[cacheId];

                if (cacheData) {
                    cacheCallback(cacheData);
                } else {
                    noCacheCallback();
                }
            };

            /**
             *
             * @param data
             * @param apiParams
             */
            self.apiError = function (data, apiParams, status, headers, config) {

                $log.error(
                    'An API error occured, status: ' + status + ' returned: ',
                    data
                );

                self.prepareErrorData(
                    data,
                    apiParams,
                    function (data) {
                        apiParams.loading(false);
                        apiParams.error(data);
                    },
                    status
                );
            };

            /**
             * apiSuccess
             * @param data
             * @param apiParams
             * @param status
             * @param headers
             * @param config
             */
            self.apiSuccess = function (data, apiParams, status, headers, config) {

                if (status != 200 || typeof data !== 'object') {

                    self.prepareErrorData(
                        data,
                        apiParams,
                        function (data) {
                            apiParams.loading(false);
                            apiParams.error(data);
                        },
                        status
                    )
                } else {

                    self.prepareData(
                        data,
                        apiParams,
                        function (data) {
                            self.setCache(apiParams.cacheId, data);
                            apiParams.loading(false);
                            apiParams.success(data);
                        }
                    );
                }
            };

            /**
             * prepareErrorData
             * @param data
             * @param apiParams
             * @param callback
             * @param status
             */
            self.prepareErrorData = function (data, apiParams, callback, status) {

                if (typeof data !== 'object' || data === null) {
                    data = new self.ApiData();
                }

                if (!data.messages) {
                    data.messages = [];
                }

                if (data.messages.length < 1) {
                    var message = new self.ApiMessage(self.config.defaultMessage);
                    data.messages.primary = true;
                    data.messages = [message];
                }

                self.prepareData(data, apiParams, callback);
            };

            /**
             * prepareData
             * @param data
             * @param apiParams
             * @param callback
             */
            self.prepareData = function (data, apiParams, callback) {

                data = angular.extend(new self.ApiData(), data);
                callback(data);
            };

            return self;
        }
    ]
);


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

/**
 * rcmApiLibMessageService
 */
angular.module('rcmApiLib')
    .factory(
    'rcmApiLibMessageService',
    [
        '$log',
        'rcmApiLibApiMessage',
        function ($log, rcmApiLibApiMessage) {

            var self = this;

            /**
             * messages
             * @type {Array}
             */
            self.messages = [];

            /**
             * addMessage
             * @param message
             */
            self.addMessage = function (message) {
                message = angular.extend(new rcmApiLibApiMessage(), message);
                self.messages.push(message);
            };

            /**
             * addMessages
             * @param messages
             */
            self.addMessages = function (messages) {
                angular.forEach(
                    messages,
                    function (message, key) {
                        self.addMessage(message);
                    }
                );
            };

            /**
             * buildPrimaryMessage
             * @param messages
             */
            self.buildPrimaryMessage = function (messages) {
                self.getPrimaryMessage(
                    messages,
                    function (primaryMessage) {
                        if(primaryMessage) {
                            self.addMessage(primaryMessage);
                        }
                    }
                )
            };

            /**
             * clearMessages
             */
            self.clearMessages = function () {
                self.messages = [];
            };

            /**
             * getDefaultMessage
             * @returns {rcmApiLibApiMessage}
             */
            self.getDefaultMessage = function() {
                return new rcmApiLibApiMessage();
            };

            /**
             * getPrimaryMessage
             * @param messages
             * @param callback
             */
            self.getPrimaryMessage = function (messages, callback) {
                var primaryMessage = null;

                if (messages) {
                    primaryMessage = messages[0];
                }

                callback(primaryMessage);
                return primaryMessage;
            };

            /**
             * getTypeMessages
             * @param messages
             * @param type
             * @param callback
             */
            self.getTypeMessages = function (messages, type, callback) {
                var typeMessages = {};

                angular.forEach(
                    messages,
                    function (message, key) {
                        if (message.type == type) {
                            this[message.source] = message.value;
                        }
                    },
                    typeMessages
                );

                callback(typeMessages);
                return typeMessages;
            };

            return self;
        }
    ]
);

angular.module('rcmApiLib')
    .directive(
    'rcmApiLibMessageDirective',
    [
        '$log',
        'rcmApiLibMessageService',
        function ($log, rcmApiLibMessageService) {

            var link = function ($scope, elm) {
                $scope.$watch(
                    function () {
                        return rcmApiLibMessageService.messages
                    },
                    function () {
                        $scope.messages = rcmApiLibMessageService.messages;
                        // Scroll to message
                        elm[0].scrollIntoView(true);
                    }
                );
                $scope.messages = rcmApiLibMessageService.messages;
            };

            return {
                link: link,
                template: '' +
                '<div class="alert alert-{{messages[0].level}}" ng-hide="messages.length < 1" role="alert">' +
                ' <div class="message" ng-repeat="message in messages">{{message.value}}</div>' +
                '</div>'

            }
        }
    ]
);
