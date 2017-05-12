/**
 * {rcmApiLibServiceConfig}
 * @type {{defaultMessage: string}}
 */
var rcmApiLibServiceConfig = {
    defaultMessage: 'An unknown error occurred while making request'
};

/**
 * rcmApiLib Module
 */
angular.module('rcmApiLib').value(
    'rcmApiLibServiceConfig',
    rcmApiLibServiceConfig
);
