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

                if (messages[0]) {
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
