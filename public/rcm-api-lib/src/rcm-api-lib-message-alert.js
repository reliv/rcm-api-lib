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
console.log(template);
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

            return self;
        }
    ]
);
