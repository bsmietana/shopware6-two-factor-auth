Shopware.Component.register('rl-user-otp', () => import('./component/rl-user-otp'));
Shopware.Component.override('sw-login-login', () => import('./override/sw-login/view/sw-login-login'));
if (Shopware.Component.getComponentRegistry().has('sw-profile-index')) {
    Shopware.Component.override('sw-profile-index', () => import('./override/sw-login/view/sw-login-login'));
}
if (Shopware.Component.getComponentRegistry().has('sw-profile-index-general')) {
    Shopware.Component.override('sw-profile-index-general', () => import('./override/sw-profile/view/sw-profile-index-general'));
}
if (Shopware.Component.getComponentRegistry().has('sw-users-permissions-user-detail')) {
    Shopware.Component.override('sw-users-permissions-user-detail', () => import('./override/sw-users-permissions/page/sw-users-permissions-user-detail'));
}
if (Shopware.Component.getComponentRegistry().has('sw-customer-base-info')) {
    Shopware.Component.override('sw-customer-base-info', () => import('./override/sw-customer/component/sw-customer-base-info'));
}

import enGB from './snippet/en-GB.json';
import nlNL from './snippet/nl-NL.json';

Shopware.Locale.extend('en-GB', enGB);
Shopware.Locale.extend('nl-NL', nlNL);
