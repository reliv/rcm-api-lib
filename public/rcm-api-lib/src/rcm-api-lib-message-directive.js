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
                var namespace = "DEFAULT";
                if(attrs.namespace) {
                    namespace = attrs.namespace;
                }
                // Clear to create namespace
                rcmApiLibMessageService.clearMessages(namespace);
                $scope.$watch(
                    function () {
                        return rcmApiLibMessageService.messages[namespace];
                    },
                    function () {
                        $scope.messages = rcmApiLibMessageService.getMessages(namespace);

                        // Scroll to message
                        if($scope.messages.length > 0) {
                            elm[0].scrollIntoView(true);
                        }
                    }
                );
                $scope.messages = rcmApiLibMessageService.getMessages(namespace);
            };

            return {
                link: link,
                template: '' +
                '<div ng-hide="messages.length < 1">' +
                ' <div ng-repeat="message in messages" class="alert alert-{{message.level}}" role="alert">' +
                '  <div class="message">{{message.value}}</div>' +
                ' </div>' +
                '</div>'
            }
        }
    ]
);
