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
     * Set the key for saving OAuth settings in GDN_UserAuthenticationProvider
     */
    public function __construct() {
        $this->setProviderKey('oauth2-openshift');
        $this->settingsView = 'plugins/settings/oauth2-openshift';
    }

    /**
     * Set proper JWT authentication header as required by the OpenShift API
     */
    public function getProfileRequestOptions() {
        return ["Authorization-Header-Message" => "Bearer $this->accessToken"];
    }

    /**
     *   Allow the admin to input the keys that their service uses to send data.
     *
     * @param array $rawProfile profile as it is returned from the provider.
     *
     * @return array Profile array transformed by child class or as is.
     */
    public function translateProfileResults($rawProfile = []) {
        $provider = $this->provider();
        $email = val('ProfileKeyEmail', $provider, 'email');
        $translatedKeys = [
            'Email' => val('ProfileKeyEmail', $provider, 'email'),
            'Photo' => val('ProfileKeyPhoto', $provider, 'picture'),
            'Name' => val('ProfileKeyName', $provider, 'displayname'),
            'FullName' => val('ProfileKeyFullName', $provider, 'name'),
            'UniqueID' => val('ProfileKeyUniqueID', $provider, 'user_id')
        ];

        $profile = [];
        foreach($translatedKeys as $key => $value) {
          $profile[$key] = $this->deepLookup($rawProfile, $value);
        }

        $profile['Provider'] = $this->providerKey;

        return $profile;
    }

    protected function deepLookup($array, $key) {
        if(isset($array[$key])) {
          return $array[$key];
        } else if(strpos($key, '.') !== false) {
          $pieces = explode(".", $key, 2);
          return $this->deepLookup($array[$pieces[0]], $pieces[1]);
        } else {
          return null;
        }
    }

}
