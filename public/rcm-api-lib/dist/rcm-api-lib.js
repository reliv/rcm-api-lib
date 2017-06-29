/**
 * rcmApiLib Module
 */
angular.module('rcmApiLib', ['RcmJsLib']);

/**
 * {rcmApiLibServiceConfig}
 * @type {{defaultMessage: string}}
 */
var rcmApiLibServiceConfig = {
    defaultMessage: 'An unknown error occurred while making request'
};

/**
 * rcmApiLib Module
 */
angular.module('rcmApiLib').value(
    'rcmApiLibServiceConfig',
    rcmApiLibServiceConfig
);

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
    function ($http, $log) {

        /**
         * Class RcmApiLibApiParams
         */
        return RcmApiLibApiParams;
    }
);

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

/**
 * {RcmApiLibServiceConfig}
 * @param value
 * @constructor
 */
var RcmApiLibServiceConfig = function (value) {
    var self = this;
    self.type = 'rcmApiLib';
    self.source = 'client';
    self.code = 'error';
    self.value = rcmApiLibServiceConfig.defaultMessage;
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
        if (value) {
            self.value = value;
        }
    };

    self.init(value);
};

/**
 * Class RcmApiLibServiceConfig
 */
angular.module('rcmApiLib').factory(
    'rcmApiLibApiMessage',
    [
        'rcmApiLibServiceConfig',
        function (rcmApiLibServiceConfig) {

            /**
             * Class RcmApiLibServiceConfig
             */
            return RcmApiLibServiceConfig;
        }
    ]
);

/**
 * {RcmApiLibService}
 * General service to wrap standard API JSON returns from rcm-api-lib
 *  - Creates standard return on error if no standard API JSON object received
 *  - Deals with loading state
 *    See: ApiParams.loading
 *
 * @param angular
 * @param $http
 * @param $log
 * @param rcmApiLibServiceConfig
 * @param rcmApiLibApiData
 * @param rcmApiLibApiMessage
 * @param rcmApiLibApiParams
 * @param Promise
 * @constructor
 */
var RcmApiLibService = function (
    angular,
    $http,
    $log,
    rcmApiLibServiceConfig,
    rcmApiLibApiData,
    rcmApiLibApiMessage,
    rcmApiLibApiParams,
    Promise
) {
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
     * getCached
     * @param apiParams
     * @param cacheId Send a cacheId or it will use the url
     * @returns {Promise}
     */
    self.getCached = function (apiParams, cacheId) {

        apiParams = self.buildApiParams(apiParams);

        apiParams.loading(true);

        if (!cacheId) {
            cacheId = apiParams.url + '?' + JSON.stringify(apiParams.params);
        }

        apiParams.cacheId = cacheId;

        var cacheData = self.getCache(cacheId);

        if (typeof cacheData === 'undefined') {
            return self.get(
                apiParams
            )
        }

        var promise = new Promise(
            function (resolve, reject) {
                apiParams.resolve = resolve;
                apiParams.reject = reject;
                self.apiSuccess(cacheData, apiParams, 200, {}, {})
            }
        );

        promise.catch(
            function () {
                // @BC - Eliminate errors if old callback is used
            }
        );

        return promise;
    };

    /**
     * GET
     * @param apiParams
     * @returns {Promise}
     */
    self.get = function (apiParams) {
        apiParams = self.buildApiParams(apiParams);

        apiParams.loading(true);

        var promise = new Promise(
            function (resolve, reject) {
                apiParams.resolve = resolve;
                apiParams.reject = reject;
                $http(
                    {
                        method: 'GET',
                        url: apiParams.url,
                        params: apiParams.params
                    }
                ).success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                ).error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            }
        );

        promise.catch(
            function () {
                // @BC - Eliminate errors if old callback is used
            }
        );

        return promise;
    };

    /**
     * POST
     * @param apiParams
     * @returns {Promise}
     */
    self.post = function (apiParams) {

        apiParams = self.buildApiParams(apiParams);

        apiParams.loading(true);

        var promise = new Promise(
            function (resolve, reject) {
                apiParams.resolve = resolve;
                apiParams.reject = reject;
                $http(
                    {
                        method: 'POST',
                        url: apiParams.url,
                        data: apiParams.data,
                        params: apiParams.params
                    }
                ).success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                ).error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            }
        );

        promise.catch(
            function () {
                // @BC - Eliminate errors if old callback is used
            }
        );

        return promise;
    };

    /**
     * PATCH
     * @param apiParams
     * @returns {Promise}
     */
    self.patch = function (apiParams) {

        apiParams = self.buildApiParams(apiParams);

        apiParams.loading(true);

        var promise = new Promise(
            function (resolve, reject) {
                apiParams.resolve = resolve;
                apiParams.reject = reject;
                $http(
                    {
                        method: 'PATCH',
                        url: apiParams.url,
                        data: apiParams.data,
                        params: apiParams.params
                    }
                ).success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                ).error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            }
        );

        promise.catch(
            function () {
                // @BC - Eliminate errors if old callback is used
            }
        );

        return promise;
    };

    /**
     * PUT
     * @param apiParams
     * @returns {Promise}
     */
    self.put = function (apiParams) {

        apiParams = self.buildApiParams(apiParams);

        apiParams.loading(true);

        var promise = new Promise(
            function (resolve, reject) {
                apiParams.resolve = resolve;
                apiParams.reject = reject;
                $http(
                    {
                        method: 'PUT',
                        url: apiParams.url,
                        data: apiParams.data,
                        params: apiParams.params
                    }
                ).success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                ).error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            }
        );

        promise.catch(
            function () {
                // @BC - Eliminate errors if old callback is used
            }
        );

        return promise;
    };

    /**
     * DELETE
     * @param apiParams
     * @returns {Promise}
     */
    self.del = function (apiParams) {

        apiParams = self.buildApiParams(apiParams);

        apiParams.loading(true);

        var promise = new Promise(
            function (resolve, reject) {
                apiParams.resolve = resolve;
                apiParams.reject = reject;
                $http(
                    {
                        method: 'DELETE',
                        url: apiParams.url,
                        data: apiParams.data,
                        params: apiParams.params
                    }
                ).success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                ).error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            }
        );

        promise.catch(
            function () {
                // @BC - Eliminate errors if old callback is used
            }
        );

        return promise;
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
     * @returns {Promise}
     */
    self.getCache = function (cacheId) {
        return self.cache[cacheId];
    };

    /**
     * apiError
     * @param data
     * @param apiParams
     * @param status
     * @param headers
     * @param config
     */
    self.apiError = function (data, apiParams, status, headers, config) {

        $log.info(
            'An API error occurred, status: ' + status + ' returned: ',
            data
        );

        self.prepareErrorData(
            data,
            apiParams,
            function (data) {
                apiParams.loading(false);
                apiParams.triggerError(data, status, headers, config);
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

        if (status != 200 || !self.isValidDataType(data, apiParams)) {
            $log.info(
                'API returned responseType (' +
                self.getDataType(data) +
                ') that is not supported or invalid status (' +
                status +
                ') data: ',
                data
            );
            self.prepareErrorData(
                data,
                apiParams,
                function (data) {
                    apiParams.loading(false);
                    apiParams.triggerError(data, status, headers, config);
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
                    apiParams.triggerSuccess(data, status, headers, config);
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
        var preparedData = self.buildValidDataFormat(data);

        if (preparedData.messages.length < 1) {
            var message = new self.ApiMessage(self.config.defaultMessage);
            preparedData.messages.primary = true;
            preparedData.messages = [message];
        }

        callback(preparedData)
    };

    /**
     * prepareData
     * @param data
     * @param apiParams
     * @param callback
     */
    self.prepareData = function (data, apiParams, callback) {
        callback(self.buildValidDataFormat(data));
    };

    /**
     * isValidDataType
     * @param data
     * @param apiParams
     * @returns {boolean}
     */
    self.isValidDataType = function (data, apiParams) {

        var dataType = self.getDataType(data);

        return (apiParams.responseTypes.indexOf(dataType) > -1);
    };

    /**
     * getDataType
     * @param data
     * @returns {*}
     */
    self.getDataType = function (data) {

        if (typeof data === 'undefined' || data === null) {
            return 'null';
        }

        if (Array.isArray(data)) {
            return 'array';
        }

        return typeof data;
    };

    /**
     * buildValidDataFormat
     * @param data
     * @returns {self.ApiData}
     */
    self.buildValidDataFormat = function (data) {
        var preparedData = new self.ApiData();

        if (self.isValidDataFormat(data)) {
            preparedData = angular.extend(preparedData, data);
            return preparedData;
        }

        preparedData.data = data;
        return preparedData;
    };

    /**
     * isValidDataFormat
     * @param data
     * @returns {boolean|string}
     */
    self.isValidDataFormat = function (data) {

        if (typeof data !== 'object' || data === null) {
            return false;
        }

        return (typeof data.messages !== 'undefined' && typeof data.data !== 'undefined' && typeof Array.isArray(
            data.messages
        ));
    };
};

/**
 *
 */
angular.module('rcmApiLib').factory(
    'rcmApiLibService',
    [
        '$http',
        '$log',
        'rcmApiLibServiceConfig',
        'rcmApiLibApiData',
        'rcmApiLibApiMessage',
        'rcmApiLibApiParams',
        function (
            $http,
            $log,
            rcmApiLibServiceConfig,
            rcmApiLibApiData,
            rcmApiLibApiMessage,
            rcmApiLibApiParams
        ) {
            return new RcmApiLibService(
                angular,
                $http,
                $log,
                rcmApiLibServiceConfig,
                rcmApiLibApiData,
                rcmApiLibApiMessage,
                rcmApiLibApiParams,
                Promise
            );
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
 * {RcmApiLibMessageAlert}
 * @param $window
 * @param angular
 * @param rcmApiLibMessageService
 * @constructor
 */
var RcmApiLibMessageAlert = function (
    $window,
    angular,
    rcmApiLibMessageService
) {
    /**
     * self
     */
    var self = this;

    self.defaultMessageTemplate = '{{value}}';
    self.defaultTypeMessageTemplate = '\n{{value}} ({{source}})';

    /**
     *
     * @param object
     * @returns {*}
     */
    var getCleanObject = function (object) {
        if (!object || typeof object !== 'object') {
            $window.console.error('object expected');
            return {};
        }

        data = angular.copy(object);

        for (var key in object) {
            if (typeof object[key] === 'string') {
                continue;
            }

            object[key] = JSON.stringify(object[key]);
        }

        return object;
    };

    /**
     *
     * @param object
     * @param template
     * @returns {*}
     */
    self.render = function (object, template) {
        if (!template) {
            template = self.defaultMessageTemplate;
        }
        object = getCleanObject(object);

        return template.replace(
            /\{\{(.*?)\}\}/g,
            function (i, match) {
                return object[match];
            }
        );
    };
    /**
     *
     * @param messages
     * @param template
     */
    self.displayMessage = function (messages, template) {
        $window.alert(self.render(message, template));
    };

    /**
     *
     * @param messages
     * @param template
     */
    self.displayMessages = function (messages, template) {
        var messageContent = '';

        for (var i = 0; i < messages.length; i++) {
            messageContent = self.render(messages[i], template);
        }

        $window.alert(messageContent);
    };

    /**
     *
     * @param type
     * @param messages
     * @param primaryTemplate
     * @param template
     */
    self.displayTypeMessages = function (type, messages, primaryTemplate, template) {

        if (!template) {
            template = self.defaultTypeMessageTemplate;
        }

        var primaryMessage = rcmApiLibMessageService.getPrimaryMessage(messages);

        var messageContent = self.render(primaryMessage, primaryTemplate);

        for (var i = 0; i < messages.length; i++) {
            if (messages[i].type && messages[i].type == type) {
                messageContent = messageContent + self.render(messages[i], template);
            }
        }

        $window.alert(messageContent);
    };
};

/**
 * rcmApiLibMessageAlert
 */
angular.module('rcmApiLib').factory(
    'rcmApiLibMessageAlert',
    [
        '$window',
        'rcmApiLibMessageService',
        function (
            $window,
            rcmApiLibMessageService
        ) {
            return new RcmApiLibMessageAlert(
                $window,
                angular,
                rcmApiLibMessageService
            );
        }
    ]
);

/**
 * {RcmApiLibMessageService}
 * @param angular
 * @param rcmApiLibApiMessage
 * @param RcmEventManagerClass
 * @constructor
 */
var RcmApiLibMessageService = function (
    angular,
    rcmApiLibApiMessage,
    RcmEventManagerClass
) {

    /**
     * self
     */
    var self = this;

    /**
     *
     * @type {RcmEventManagerClass|*}
     */
    var eventManager = new RcmEventManagerClass();

    /**
     * defaultNamespace
     * @type {string}
     */
    var defaultNamespace = 'DEFAULT';

    /**
     * messages
     * @type {{}}
     */
    self.messages = {};

    /**
     * getEventManager
     * @returns {RcmEventManager}
     */
    self.getEventManager = function () {
        return eventManager;
    };

    /**
     * getDefaultNamespace
     * @returns {string}
     */
    self.getDefaultNamespace = function () {
        return defaultNamespace;
    };

    /**
     * getNamespace
     * @param namespace
     * @returns {*}
     */
    var getNamespace = function (namespace) {
        if (typeof namespace !== 'string') {
            namespace = defaultNamespace
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
        eventManager.trigger('rcmApiLibApiMessage.addMessage', {namespace: namespace, message: message});
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
        eventManager.trigger('rcmApiLibApiMessage.clearMessages', namespace);
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
        eventManager.trigger('rcmApiLibApiMessage.createNamespace', namespace);
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
     * setMessage
     * @param message
     * @param namespace
     */
    self.setMessage = function (message, namespace) {
        self.clearMessages(namespace);
        self.addMessage(message, namespace);
    };

    /**
     * setMessages
     * @param messages
     * @param namespace
     */
    self.setMessages = function (messages, namespace) {
        self.clearMessages(namespace);
        self.addMessages(messages, namespace)
    };

    /**
     * addMessage
     * @param message
     * @param namespace
     */
    self.addMessage = function (message, namespace) {
        if (!self.isValidMessage(message)) {
            console.warn(
                "rcmApiLibApiMessage.addMessage received an invalid message",
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
        eventManager.trigger(
            'rcmApiLibApiMessage.addMessages',
            {namespace: namespace, messages: self.getMessages(namespace)}
        );
    };

    /**
     * getMessages
     * @param namespace
     * @returns {[]}
     */
    self.getMessages = function (namespace) {
        return getNamespaceMessages(namespace);
    };

    /**
     * setPrimaryMessage
     * @param messages
     * @param namespace
     * @param callback
     */
    self.setPrimaryMessage = function (messages, namespace, callback) {
        self.getPrimaryMessage(
            messages,
            function (primaryMessage) {
                if (primaryMessage) {
                    self.setMessage(primaryMessage, namespace);
                    if (typeof callback === 'function') {
                        callback(primaryMessage)
                    }
                }
            }
        )
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
                    if (typeof callback === 'function') {
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

        if (typeof callback === 'function') {
            callback(primaryMessage)
        }
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
};

/**
 * rcmApiLibMessageService
 */
angular.module('rcmApiLib').factory(
    'rcmApiLibMessageService',
    [
        'rcmApiLibApiMessage',
        'RcmEventManagerClass',
        function (rcmApiLibApiMessage, RcmEventManagerClass) {
            return new RcmApiLibMessageService(
                angular,
                rcmApiLibApiMessage,
                RcmEventManagerClass
            );
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
            '$window',
            'rcmApiLibMessageService',
            function (
                $window,
                rcmApiLibMessageService
            ) {

                /**
                 * link
                 * @param $scope
                 * @param elm
                 * @param attrs
                 */
                var link = function ($scope, elm, attrs) {

                    var log = function (message, data) {
                        if (!$scope.debug) {
                            return;
                        }

                        $window.console.log(message, data)
                    };

                    var eventManager = rcmApiLibMessageService.getEventManager();

                    if (!$scope.namespace) {
                        $scope.namespace = rcmApiLibMessageService.getDefaultNamespace();
                    }

                    log('initNamespace', $scope.namespace);

                    /**
                     * Create namespace
                     */
                    rcmApiLibMessageService.createNamespace($scope.namespace);

                    $scope.apiLibDirectiveMessages = {};

                    var scrollToMessage = function () {
                        log('scrollToMessage', null);
                        var position = elm.position();
                        $window.scroll(0, position.top);
                    };

                    /**
                     * setMessage
                     * @param result
                     */
                    var setMessages = function (result) {
                        var messages = rcmApiLibMessageService.getMessages(result.namespace);
                        log('setMessages', messages);
                        // spam protection
                        if ($scope.apiLibDirectiveMessages[result.namespace] === messages) {
                            return;
                        }
                        $scope.apiLibDirectiveMessages[result.namespace] = messages;

                        // Scroll to message
                        if (messages.length > 0) {
                            scrollToMessage()
                        }
                    };

                    /**
                     * clearMessages
                     * @param namespace
                     */
                    var clearMessages = function (namespace) {
                        log('clearMessages', namespace);
                        $scope.apiLibDirectiveMessages[namespace] = [];
                    };

                    /**
                     *
                     */
                    eventManager.on(
                        'rcmApiLibApiMessage.addMessages',
                        setMessages,
                        $scope.namespace
                    );

                    /**
                     *
                     */
                    eventManager.on(
                        'rcmApiLibApiMessage.addMessage',
                        setMessages,
                        $scope.namespace
                    );

                    /**
                     *
                     */
                    eventManager.on(
                        'rcmApiLibApiMessage.clearMessages',
                        function (response) {
                            clearMessages($scope.namespace);
                        },
                        $scope.namespace
                    );

                    $scope.apiLibDirectiveMessages[$scope.namespace] = rcmApiLibMessageService.getMessages($scope.namespace);
                };

                return {
                    link: link,
                    scope: {
                        namespace: '@namespace',
                        debug: '@debug'
                    },
                    template: '<div ng-if="debug"><pre>{{apiLibDirectiveMessages[namespace] | json}}</pre></div>' +
                    '<div ng-show="apiLibDirectiveMessages[namespace].length">' +
                    ' <div ng-repeat="message in apiLibDirectiveMessages[namespace]" class="alert alert-{{message.level}}" role="alert">' +
                    '  <div class="message">{{message.value}}</div>' +
                    ' </div>' +
                    '</div>'
                }
            }
        ]
    );
