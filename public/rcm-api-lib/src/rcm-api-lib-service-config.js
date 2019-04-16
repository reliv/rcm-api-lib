/**
 * {rcmApiLibServiceConfig}
 * @type {{defaultMessage: string}}
 */
window.rcmApiLibServiceConfig = {
    defaultMessage: 'An unknown error occurred while making request'
};

/**
 * rcmApiLib Module
 */
angular.module('rcmApiLib').value(
    'rcmApiLibServiceConfig',
    rcmApiLibServiceConfig
);
