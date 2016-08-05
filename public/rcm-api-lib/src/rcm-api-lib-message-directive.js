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

                    // Create namespace
                    rcmApiLibMessageService.createNamespace($scope.namespace);
                    $scope.apiLibDirectiveMessages = {};

                    var setMessages = function (namespace, messages) {
                        $scope.apiLibDirectiveMessages[namespace] = messages;
                        // Scroll to message
                        if (messages.length) {
                            elm[0].scrollIntoView(true);
                        }
                    };

                    var clearMessages = function (namespace) {
                        $scope.apiLibDirectiveMessages[namespace] = [];
                    };

                    eventManager.on(
                        'rcmApiLibApiMessage.addMessage',
                        function (response) {
                            setMessages(
                                $scope.namespace,
                                rcmApiLibMessageService.getMessages($scope.namespace)
                            );
                        },
                        $scope.namespace
                    );

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
