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
             * @type {Array}
             */
            self.messages = [];

            /**
             * isValidMessage
             * @param message
             * @returns boolean
             */
            self.isValidMessage = function (message) {
                if(!message) {
                    return false;
                }
                return (message.type && message.source);
            };

            /**
             * addMessage
             * @param message
             */
            self.addMessage = function (message) {
                if(!self.isValidMessage(message)) {
                    console.warn("rcmApiLibApiMessage.addMessage recieved an invalid message", message)
                    return;
                }
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
                        if (primaryMessage) {
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

                if(typeof callback === 'function') {
                    callback(typeMessages);
                }
                return typeMessages;
            };

            return self;
        }
    ]
);
