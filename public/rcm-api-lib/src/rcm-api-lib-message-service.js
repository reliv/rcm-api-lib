/**
 * rcmApiLibMessageService
 */
angular.module('rcmApiLib')
    .factory(
        'rcmApiLibMessageService',
        [
            '$log',
            'rcmApiLibApiMessage',
            'RcmEventManagerClass',
            function ($log, rcmApiLibApiMessage, RcmEventManagerClass) {

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

                    if(typeof callback === 'function') {
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

                return self;
            }
        ]
    );
