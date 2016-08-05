/**
 * rcmApiLibMessageDirective
 * <rcm-api-lib-message-directive namespace="{MY_MESSAGE_NAMESPACE}"></rcm-api-lib-message-directive>
 */
angular.module('rcmApiLib')
    .directive(
        'rcmApiLibMessageDirective',
        [
            '$log',
            'rcmApiLibMessageService',
            function ($log, rcmApiLibMessageService) {

                /**
                 * link
                 * @param $scope
                 * @param elm
                 * @param attrs
                 */
                var link = function ($scope, elm, attrs) {

                    var eventManager = rcmApiLibMessageService.getEventManager();

                    if(!$scope.namespace) {
                        $scope.namespace = rcmApiLibMessageService.getDefaultNamespace();
                    }

                    /**
                     * Create namespace
                     */
                    rcmApiLibMessageService.createNamespace($scope.namespace);

                    $scope.apiLibDirectiveMessages = {};

                    var scrollToMessage = function () {
                        elm[0].scrollIntoView(true);
                    };

                    /**
                     * setMessage
                     * @param result
                     */
                    var setMessage = function (result) {
                        var messages = [result.message];
                        $scope.apiLibDirectiveMessages[result.namespace] = messages;

                        // Scroll to message
                        if (messages.length) {
                            scrollToMessage()
                        }
                    };

                    /**
                     * setMessages
                     * @param result
                     */
                    var setMessages = function (result) {
                        $scope.apiLibDirectiveMessages[result.namespace] = result.messages;

                        // Scroll to message
                        if (result.messages.length) {
                            elm[0].scrollIntoView(true);
                        }
                    };

                    /**
                     * clearMessages
                     * @param namespace
                     */
                    var clearMessages = function (namespace) {
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
                        setMessage,
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
                        namespace: '@namespace'
                    },
                    template: '' +
                    '<div ng-show="apiLibDirectiveMessages[namespace].length">' +
                    ' <div ng-repeat="message in apiLibDirectiveMessages[namespace]" class="alert alert-{{message.level}}" role="alert">' +
                    '  <div class="message">{{message.value}}</div>' +
                    ' </div>' +
                    '</div>'
                }
            }
        ]
    );
