angular.module('rcmApiLib')
    .directive(
    'rcmApiLibMessageDirective',
    [
        '$log',
        function ($log) {

            function link($scope) {

            };

            return {
                link: link,
                template: '<div class="alert alert-danger" role="alert">' +
                '<div class="message" ng-repeat="message in messages">{{message}}</div>' +
                '</div>'
            }
        }
    ]
);
