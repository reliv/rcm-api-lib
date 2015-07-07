angular.module('rcmApiLib')
    .directive(
    'rcmApiLibMessageDirective',
    [
        '$log',
        'rcmApiLibMessageService',
        function ($log, rcmApiLibMessageService) {

            var link = function ($scope) {
                $scope.$watch(
                    function () {
                        return rcmApiLibMessageService.messages
                    },
                    function () {
                        $scope.messages = rcmApiLibMessageService.messages;
                    }
                );
                $scope.messages = rcmApiLibMessageService.messages;
            };

            return {
                link: link,
                template: '' +
                '<div class="alert alert-danger" ng-hide="messages.length < 1" role="alert">' +
                ' <div class="message" ng-repeat="message in messages">{{message.value}}</div>' +
                '</div>'

            }
        }
    ]
);
