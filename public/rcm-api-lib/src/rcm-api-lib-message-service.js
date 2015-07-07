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
             * types
             * @type {{inputFilter: string}}
             */
            self.types = {
                inputFilter: 'inputFilter'
            };

            /**
             * addMessage
             * @param message
             */
            self.addMessage = function (message) {
                message = angular.extend(new rcmApiLibApiMessage(), message);
                self.messages.push(message);
            };

            /**
             * addPrimaryMessage
             * @param messages
             */
            self.addPrimaryMessage = function (messages) {
                self.getPrimaryMessage(
                    messages,
                    function (primaryMessage) {
                        self.addMessage(primaryMessage);
                    }
                )
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
             * clearMessages
             */
            self.clearMessages = function () {
                self.messages = [];
            };

            /**
             * getPrimaryMessage
             * @param messages
             * @param callback
             */
            self.getPrimaryMessage = function (messages, callback) {
                var primaryMessage = null;

                if (messages.length > 0) {
                    primaryMessage = messages[0];
                }

                callback(primaryMessage);
            };

            /**
             * getTypeMessages
             * @param messages
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
            };

            return self;
        }
    ]
);
