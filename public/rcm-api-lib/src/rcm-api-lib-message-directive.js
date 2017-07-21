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
                var getElementOffset = function (element) {
                    var de = document.documentElement;
                    var box = element.getBoundingClientRect();
                    var top = box.top + window.pageYOffset - de.clientTop;
                    var left = box.left + window.pageXOffset - de.clientLeft;
                    return {
                        top: top,
                        left: left
                    };
                };

                var log = function (message, data, debug) {
                    if (!debug) {
                        return;
                    }

                    $window.console.log(message, data)
                };

                var scrollToMessage = function (elm, debug) {

                    $window.setTimeout(
                        function () {
                            //var position = elm.position();
                            var position = getElementOffset(elm[0]);
                            $window.scroll(0, position.top);
                            log(
                                'scrollToMessage',
                                {
                                    position: position,
                                    elm: elm
                                },
                                debug
                            );
                        }
                    )
                };

                /**
                 * link
                 * @param $scope
                 * @param elm
                 * @param attrs
                 */
                var link = function ($scope, elm, attrs) {
                    var eventManager = rcmApiLibMessageService.getEventManager();

                    if (!$scope.namespace) {
                        $scope.namespace = rcmApiLibMessageService.getDefaultNamespace();
                    }

                    log('initNamespace', $scope.namespace, $scope.debug);

                    /**
                     * Create namespace
                     */
                    rcmApiLibMessageService.createNamespace($scope.namespace);

                    $scope.apiLibDirectiveMessages = {};

                    /**
                     * setMessage
                     * @param result
                     */
                    var setMessages = function (result) {
                        var messages = rcmApiLibMessageService.getMessages(result.namespace);
                        log('setMessages', messages, $scope.debug);
                        // spam protection
                        if ($scope.apiLibDirectiveMessages[result.namespace] === messages) {
                            return;
                        }
                        $scope.apiLibDirectiveMessages[result.namespace] = messages;

                        // Scroll to message
                        if (messages.length > 0) {
                            var position = scrollToMessage(elm, $scope.debug);
                        }
                    };

                    /**
                     * clearMessages
                     * @param namespace
                     */
                    var clearMessages = function (namespace) {
                        log('clearMessages', namespace, $scope.debug);
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
                    template: '' +
                    '<div ng-show="apiLibDirectiveMessages[namespace].length">' +
                    ' <div ng-repeat="message in apiLibDirectiveMessages[namespace]" class="alert alert-{{message.level}}" role="alert">' +
                    '  <div class="message">{{message.value}}</div>' +
                    ' </div>' +
                    '</div>' +
                    '<div ng-if="debug"><textarea>DEBUG:\n{{apiLibDirectiveMessages[namespace] | json}}</textarea></div>'
                }
            }
        ]
    );
