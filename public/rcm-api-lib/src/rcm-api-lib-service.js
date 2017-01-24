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
                 * getCached
                 * @param apiParams
                 * @param cacheId Send a cacheId or it will use the url
                 * @returns {Promise}
                 */
                self.getCached = function (apiParams, cacheId) {

                    apiParams = self.buildApiParams(apiParams);

                    apiParams.loading(true);

                    if (!cacheId) {
                        cacheId = apiParams.url + JSON.stringify(apiParams.urlParams);
                    }

                    return new Promise(
                        function (resolve, reject) {
                            self.getCache(
                                cacheId
                            ).then(
                                resolve
                            ).catch(
                                function () {
                                    apiParams.cacheId = cacheId;

                                    self.get(
                                        apiParams
                                    ).then(
                                        resolve
                                    ).catch(
                                        reject
                                    )
                                }
                            )
                        }
                    );
                };

                /**
                 * GET
                 * @param apiParams
                 * @returns {Promise}
                 */
                self.get = function (apiParams) {

                    apiParams = self.buildApiParams(apiParams);

                    apiParams.loading(true);

                    return new Promise(
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
                };

                /**
                 * POST
                 * @param apiParams
                 * @returns {Promise}
                 */
                self.post = function (apiParams) {

                    apiParams = self.buildApiParams(apiParams);

                    apiParams.loading(true);

                    return new Promise(
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
                };

                /**
                 * PATCH
                 * @param apiParams
                 * @returns {Promise}
                 */
                self.patch = function (apiParams) {

                    apiParams = self.buildApiParams(apiParams);

                    apiParams.loading(true);

                    return new Promise(
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
                };

                /**
                 * PUT
                 * @param apiParams
                 * @returns {Promise}
                 */
                self.put = function (apiParams) {

                    apiParams = self.buildApiParams(apiParams);

                    apiParams.loading(true);

                    return new Promise(
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
                };

                /**
                 * DELETE
                 * @param apiParams
                 * @returns {Promise}
                 */
                self.del = function (apiParams) {

                    apiParams = self.buildApiParams(apiParams);

                    apiParams.loading(true);

                    return new Promise(
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
                 * @returns {Promise}
                 */
                self.getCache = function (cacheId, cacheCallback, noCacheCallback) {

                    var cacheData = self.cache[cacheId];

                    return new Promise(
                        function (resolve, reject) {
                            if (cacheData) {
                                cacheCallback(cacheData);
                                resolve(cacheData)
                            } else {
                                noCacheCallback();
                                reject();
                            }
                        }
                    );
                };

                /**
                 *
                 * @param data
                 * @param apiParams
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
                            apiParams.error(data, status, headers, config);
                            apiParams.reject(data, status, headers, config);
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
                                apiParams.error(data, status, headers, config);
                                apiParams.reject(data, status, headers, config);
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
                                apiParams.resolve(data, status, headers, config);
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
