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
