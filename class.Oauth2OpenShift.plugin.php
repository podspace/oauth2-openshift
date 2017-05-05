<?php
/**
 * @copyright 2009-2017 Vanilla Forums Inc.
 * @license Proprietary
 */

$PluginInfo['oauth2-openshift'] = array(
    'Name' => 'OpenShift OAuth2 SSO',
    'ClassName' => "OAuth2OpenShiftPlugin",
    'Description' => 'Allow users to log in with their OpenShift credentials.',
    'Version' => '1.0.0',
    'RequiredApplications' => array('Vanilla' => '2.2'),
    'SettingsUrl' => '/settings/oauth2-openshift',
    'SettingsPermission' => 'Garden.Settings.Manage',
    'MobileFriendly' => true,
    'Icon' => 'openshift-logo.png',
    'Author' => "Miklos Balazs",
    'AuthorEmail' => 'mbalazs@gmail.com',
    'AuthorUrl' => 'https://www.podspace.io'
);

/**
 * Class OAuth2OpenShiftPlugin
 *
 * Expose the functionality of the core class Gdn_OAuth2 to create SSO workflows.
 */

class OAuth2OpenShiftPlugin extends Gdn_OAuth2 {
    /**
     * @var string Sets the settings view in the dashboard.
     */
    protected $settingsView = 'settings/oauth2-openshift';


    /**
     * Set the key for saving OAuth settings in GDN_UserAuthenticationProvider
     */
    public function __construct() {
        $this->setProviderKey('oauth2-openshift');
    }
}
