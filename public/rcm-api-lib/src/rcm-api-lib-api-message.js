/**
 * Class ApiMessage
 */
angular.module('rcmApiLib').factory(
    'rcmApiLibApiMessage',
    [
        'rcmApiLibServiceConfig',
        function (rcmApiLibServiceConfig) {

            /**
             * Class ApiMessage
             */
            return function (value) {
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
        }
    ]
);
