angular.module('rcmApiLib')
    .directive(
    'rcmApiLibMessageDirective',
    [
        '$log',
        'rcmApiLibMessageService',
        function ($log, rcmApiLibMessageService) {

            var link = function ($scope, elm) {
                $scope.$watch(
                    function () {
                        return rcmApiLibMessageService.messages
                    },
                    function () {
                        $scope.messages = rcmApiLibMessageService.messages;

                        // Scroll to message
                        if($scope.messages.length > 0) {
                            elm[0].scrollIntoView(true);
                        }
                    }
                );
                $scope.messages = rcmApiLibMessageService.messages;
            };

            return {
                link: link,
                template: '' +
                '<div class="alert alert-{{messages[0].level}}" ng-hide="messages.length < 1" role="alert">' +
                ' <div class="message" ng-repeat="message in messages">{{message.value}}</div>' +
                '</div>'

            }
        }
    ]
);
