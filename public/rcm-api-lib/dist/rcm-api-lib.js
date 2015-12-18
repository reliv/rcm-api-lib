/**
 * rcmApiLib Module
 */
angular.module('rcmApiLib', []);

/**
 * rcmApiLib Module
 */
angular.module('rcmApiLib').value(
    'rcmApiLibServiceConfig',
    {
        defaultMessage: 'An unknown error occured while making request'
    }
);

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
        'rcmApiLibServiceConfig',
        'rcmApiLibApiData',
        'rcmApiLibApiMessage',
        'rcmApiLibApiParams',
        function ($http, $log, rcmApiLibServiceConfig, rcmApiLibApiData, rcmApiLibApiMessage, rcmApiLibApiParams) {

            var self = this;

            /**
             * config
             * @type {rcmApiLibServiceConfig|*}
             */
            self.config = rcmApiLibServiceConfig;

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
                var apiParamsObject = new self.ApiParams();
                apiParamsObject.populate(apiParams);
                apiParamsObject.url = self.formatUrl(apiParamsObject.url, apiParamsObject.urlParams);

                return apiParamsObject;
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
                        apiParams.error(data, status, headers, config);
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
                            apiParams.error(data, status, headers, config);
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
                            apiParams.success(data, status, headers, config);
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

                /**
                 * self
                 */
                var self = this;

                /**
                 * messages
                 * @type {{}}
                 */
                self.messages = {};

                /**
                 * getNamespace
                 * @param namespace
                 * @returns {*}
                 */
                var getNamespace = function (namespace) {
                    if (typeof namespace !== 'string') {
                        namespace = "DEFAULT"
                    }
                    return namespace;
                };

                /**
                 * addNamespaceMessage
                 * @param namespace
                 * @param message
                 */
                var addNamespaceMessage = function (namespace, message) {
                    namespace = self.createNamespace(namespace);
                    self.messages[namespace].push(message);
                };

                /**
                 * getNamespaceMessages
                 * @param namespace
                 * @returns {*}
                 */
                var getNamespaceMessages = function (namespace) {
                    namespace = getNamespace(namespace);
                    if (!self.messages[namespace]) {
                        return [];
                    }

                    return self.messages[namespace];
                };

                /**
                 * clearNamespaceMessages
                 * @param namespace
                 */
                var clearNamespaceMessages = function (namespace) {
                    namespace = getNamespace(namespace);

                    self.messages[namespace] = [];
                };

                /**
                 * createNamespace
                 * @param namespace
                 */
                self.createNamespace = function (namespace) {
                    namespace = getNamespace(namespace);
                    if (!self.messages[namespace]) {
                        self.messages[namespace] = []
                    }
                    return namespace;
                };

                /**
                 * hasMessages
                 * @param namespace
                 * @returns {boolean}
                 */
                self.hasMessages = function (namespace) {
                    var messages = getNamespaceMessages(namespace);
                    return (messages.length <= 0);
                };

                /**
                 * isValidMessage
                 * @param message
                 * @returns boolean
                 */
                self.isValidMessage = function (message) {
                    if (!message) {
                        return false;
                    }
                    return (message.type && message.source);
                };

                /**
                 * addMessage
                 * @param message
                 * @param namespace
                 */
                self.addMessage = function (message, namespace) {
                    if (!self.isValidMessage(message)) {
                        console.warn(
                            "rcmApiLibApiMessage.addMessage recieved an invalid message",
                            message
                        );
                        return;
                    }
                    message = angular.extend(new rcmApiLibApiMessage(), message);

                    addNamespaceMessage(namespace, message);
                };

                /**
                 * addMessages
                 * @param messages
                 * @param namespace
                 */
                self.addMessages = function (messages, namespace) {
                    angular.forEach(
                        messages,
                        function (message, key) {
                            self.addMessage(message, namespace);
                        }
                    );
                };

                /**
                 * getMessages
                 * @param namespace
                 * @returns {[]}
                 */
                self.getMessages = function(namespace) {
                    return getNamespaceMessages(namespace);
                };

                /**
                 * addPrimaryMessage
                 * @param messages
                 * @param namespace
                 * @param callback
                 */
                self.addPrimaryMessage = function (messages, namespace, callback) {
                    self.getPrimaryMessage(
                        messages,
                        function (primaryMessage) {
                            if (primaryMessage) {
                                self.addMessage(primaryMessage, namespace);
                                if(typeof callback === 'function') {
                                    callback(primaryMessage)
                                }
                            }
                        }
                    )
                };

                /**
                 * buildPrimaryMessage
                 * @param messages
                 * @param namespace
                 * @param callback
                 */
                self.buildPrimaryMessage = function (messages, namespace, callback) {
                    self.addPrimaryMessage(messages, namespace, callback);
                };

                /**
                 * clearMessages
                 * @param namespace
                 */
                self.clearMessages = function (namespace) {
                    clearNamespaceMessages(namespace);
                };

                /**
                 * getDefaultMessage
                 * @returns {rcmApiLibApiMessage}
                 */
                self.getDefaultMessage = function () {
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
                 * @returns {"source": "value"}
                 */
                self.getTypeMessages = function (messages, type, callback) {
                    var typeMessages = {};

                    angular.forEach(
                        messages,
                        function (message, key) {
                            if (message.type == type) {
                                if (this[message.source] === undefined) {
                                    this[message.source] = [];
                                }
                                this[message.source].push(message.value);
                            }
                        },
                        typeMessages
                    );

                    if (typeof callback === 'function') {
                        callback(typeMessages);
                    }
                    return typeMessages;
                };

                return self;
            }
        ]
    );

/**
 * rcmApiLibMessageDirective
 * <rcm-api-lib-message-directive namespace="{MY_MESSAGE_NAMESPACE}"></rcm-api-lib-message-directive>
 */
angular.module('rcmApiLib')
    .directive(
    'rcmApiLibMessageDirective',
    [
        '$log',
        'rcmApiLibMessageService',
        function ($log, rcmApiLibMessageService) {

            /**
             * link
             * @param $scope
             * @param elm
             * @param attrs
             */
            var link = function ($scope, elm, attrs) {
                var namespace = "DEFAULT";
                if(attrs.namespace) {
                    namespace = attrs.namespace;
                }
                // Create namespace
                rcmApiLibMessageService.createNamespace(namespace);
                $scope.$watch(
                    function () {
                        return rcmApiLibMessageService.messages[namespace];
                    },
                    function () {
                        $scope.messages = rcmApiLibMessageService.getMessages(namespace);

                        // Scroll to message
                        if($scope.messages.length > 0) {
                            elm[0].scrollIntoView(true);
                        }
                    }
                );
                $scope.messages = rcmApiLibMessageService.getMessages(namespace);
            };

            return {
                link: link,
                scope: {},
                template: '' +
                '<div ng-hide="messages.length < 1">' +
                ' <div ng-repeat="message in messages" class="alert alert-{{message.level}}" role="alert">' +
                '  <div class="message">{{message.value}}</div>' +
                ' </div>' +
                '</div>'
            }
        }
    ]
);
