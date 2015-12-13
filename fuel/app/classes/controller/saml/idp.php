<?php

class Controller_Saml_Idp extends Controller
{
    public function get_metadata()
    {
        SimpleSAML_Configuration::setConfigDir(APPPATH.'config/simplesaml');
        $config = SimpleSAML_Configuration::getInstance();
        $metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();

        if (!$config->getBoolean('enable.saml20-idp', false)) {
            throw new SimpleSAML_Error_Error('NOACCESS');
        }
        // check if valid local session exists
        if ($config->getBoolean('admin.protectmetadata', false)) {
            SimpleSAML\Utils\Auth::requireAdmin();
        }

        try {
            $idpentityid = isset($_GET['idpentityid']) ?
                $_GET['idpentityid'] :
                $metadata->getMetaDataCurrentEntityID('saml20-idp-hosted');
            $idpmeta = $metadata->getMetaDataConfig($idpentityid, 'saml20-idp-hosted');
            $availableCerts = array();
            $keys = array();
            $certInfo = SimpleSAML\Utils\Crypto::loadPublicKey($idpmeta, false, 'new_');
            if ($certInfo !== null) {
                $availableCerts['new_idp.crt'] = $certInfo;
                $keys[] = array(
                    'type'            => 'X509Certificate',
                    'signing'         => true,
                    'encryption'      => true,
                    'X509Certificate' => $certInfo['certData'],
                );
                $hasNewCert = true;
            } else {
                $hasNewCert = false;
            }
            $certInfo = SimpleSAML\Utils\Crypto::loadPublicKey($idpmeta, true);
            $availableCerts['idp.crt'] = $certInfo;
            $keys[] = array(
                'type'            => 'X509Certificate',
                'signing'         => true,
                'encryption'      => ($hasNewCert ? false : true),
                'X509Certificate' => $certInfo['certData'],
            );
            if ($idpmeta->hasValue('https.certificate')) {
                $httpsCert = SimpleSAML\Utils\Crypto::loadPublicKey($idpmeta, true, 'https.');
                assert('isset($httpsCert["certData"])');
                $availableCerts['https.crt'] = $httpsCert;
                $keys[] = array(
                    'type'            => 'X509Certificate',
                    'signing'         => true,
                    'encryption'      => false,
                    'X509Certificate' => $httpsCert['certData'],
                );
            }
            $metaArray = array(
                'metadata-set' => 'saml20-idp-remote',
                'entityid'     => $idpentityid,
            );
            $ssob = $metadata->getGenerated('SingleSignOnServiceBinding', 'saml20-idp-hosted');
            $slob = $metadata->getGenerated('SingleLogoutServiceBinding', 'saml20-idp-hosted');
            $ssol = $metadata->getGenerated('SingleSignOnService', 'saml20-idp-hosted');
            $slol = $metadata->getGenerated('SingleLogoutService', 'saml20-idp-hosted');
            if (is_array($ssob)) {
                foreach ($ssob as $binding) {
                    $metaArray['SingleSignOnService'][] = array(
                        'Binding'  => $binding,
                        'Location' => $ssol,
                    );
                }
            } else {
                $metaArray['SingleSignOnService'][] = array(
                    'Binding'  => $ssob,
                    'Location' => $ssol,
                );
            }
            if (is_array($slob)) {
                foreach ($slob as $binding) {
                    $metaArray['SingleLogoutService'][] = array(
                        'Binding'  => $binding,
                        'Location' => $slol,
                    );
                }
            } else {
                $metaArray['SingleLogoutService'][] = array(
                    'Binding'  => $slob,
                    'Location' => $slol,
                );
            }
            if (count($keys) === 1) {
                $metaArray['certData'] = $keys[0]['X509Certificate'];
            } else {
                $metaArray['keys'] = $keys;
            }
            if ($idpmeta->getBoolean('saml20.sendartifact', false)) {
                // Artifact sending enabled
                $metaArray['ArtifactResolutionService'][] = array(
                    'index'    => 0,
                    'Location' => \SimpleSAML\Utils\HTTP::getBaseURL().'saml2/idp/ArtifactResolutionService.php',
                    'Binding'  => SAML2_Const::BINDING_SOAP,
                );
            }
            if ($idpmeta->getBoolean('saml20.hok.assertion', false)) {
                // Prepend HoK SSO Service endpoint.
                array_unshift($metaArray['SingleSignOnService'], array(
                    'hoksso:ProtocolBinding' => SAML2_Const::BINDING_HTTP_REDIRECT,
                    'Binding'                => SAML2_Const::BINDING_HOK_SSO,
                    'Location'               => \SimpleSAML\Utils\HTTP::getBaseURL().'saml2/idp/SSOService.php'
                ));
            }
            $metaArray['NameIDFormat'] = $idpmeta->getString(
                'NameIDFormat',
                'urn:oasis:names:tc:SAML:2.0:nameid-format:transient'
            );
            if ($idpmeta->hasValue('OrganizationName')) {
                $metaArray['OrganizationName'] = $idpmeta->getLocalizedString('OrganizationName');
                $metaArray['OrganizationDisplayName'] = $idpmeta->getLocalizedString(
                    'OrganizationDisplayName',
                    $metaArray['OrganizationName']
                );
                if (!$idpmeta->hasValue('OrganizationURL')) {
                    throw new SimpleSAML_Error_Exception('If OrganizationName is set, OrganizationURL must also be set.');
                }
                $metaArray['OrganizationURL'] = $idpmeta->getLocalizedString('OrganizationURL');
            }
            if ($idpmeta->hasValue('scope')) {
                $metaArray['scope'] = $idpmeta->getArray('scope');
            }
            if ($idpmeta->hasValue('EntityAttributes')) {
                $metaArray['EntityAttributes'] = $idpmeta->getArray('EntityAttributes');
                // check for entity categories
                if (SimpleSAML\Utils\Config\Metadata::isHiddenFromDiscovery($metaArray)) {
                    $metaArray['hide.from.discovery'] = true;
                }
            }
            if ($idpmeta->hasValue('UIInfo')) {
                $metaArray['UIInfo'] = $idpmeta->getArray('UIInfo');
            }
            if ($idpmeta->hasValue('DiscoHints')) {
                $metaArray['DiscoHints'] = $idpmeta->getArray('DiscoHints');
            }
            if ($idpmeta->hasValue('RegistrationInfo')) {
                $metaArray['RegistrationInfo'] = $idpmeta->getArray('RegistrationInfo');
            }
            if ($idpmeta->hasValue('validate.authnrequest')) {
                $metaArray['sign.authnrequest'] = $idpmeta->getBoolean('validate.authnrequest');
            }
            if ($idpmeta->hasValue('redirect.validate')) {
                $metaArray['redirect.sign'] = $idpmeta->getBoolean('redirect.validate');
            }
            if ($idpmeta->hasValue('contacts')) {
                $contacts = $idpmeta->getArray('contacts');
                foreach ($contacts as $contact) {
                    $metaArray['contacts'][] = \SimpleSAML\Utils\Config\Metadata::getContact($contact);
                }
            }
            $technicalContactEmail = $config->getString('technicalcontact_email', false);
            if ($technicalContactEmail && $technicalContactEmail !== 'na@example.org') {
                $techcontact['emailAddress'] = $technicalContactEmail;
                $techcontact['name'] = $config->getString('technicalcontact_name', null);
                $techcontact['contactType'] = 'technical';
                $metaArray['contacts'][] = \SimpleSAML\Utils\Config\Metadata::getContact($techcontact);
            }
            $metaBuilder = new SimpleSAML_Metadata_SAMLBuilder($idpentityid);
            $metaBuilder->addMetadataIdP20($metaArray);
            $metaBuilder->addOrganizationInfo($metaArray);
            $metaxml = $metaBuilder->getEntityDescriptorText();
            $metaflat = '$metadata['.var_export($idpentityid, true).'] = '.var_export($metaArray, true).';';
            // sign the metadata if enabled
            $metaxml = SimpleSAML_Metadata_Signer::sign($metaxml, $idpmeta->toArray(), 'SAML 2 IdP');
            if (array_key_exists('output', $_GET) && $_GET['output'] == 'xhtml') {
                $defaultidp = $config->getString('default-saml20-idp', null);
                $t = new SimpleSAML_XHTML_Template($config, 'metadata.php', 'admin');
                $t->data['clipboard.js'] = true;
                $t->data['available_certs'] = $availableCerts;
                $t->data['header'] = 'saml20-idp';
                $t->data['metaurl'] = \SimpleSAML\Utils\HTTP::getSelfURLNoQuery();
                $t->data['metadata'] = htmlspecialchars($metaxml);
                $t->data['metadataflat'] = htmlspecialchars($metaflat);
                $t->data['defaultidp'] = $defaultidp;
                $t->show();
            } else {
                header('Content-Type: application/xml');
                echo $metaxml;
                exit(0);
            }
        } catch (Exception $exception) {
            throw new SimpleSAML_Error_Error('METADATA: '.$exception->getMessage(), $exception);
        }
    }

    public function get_sso()
    {
        SimpleSAML_Configuration::setConfigDir(APPPATH.'config/simplesaml');
        $metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();
        $idpEntityId = $metadata->getMetaDataCurrentEntityID('saml20-idp-hosted');
        $idp = SimpleSAML_IdP::getById('saml2:' . $idpEntityId);
        try {
            sspmod_saml_IdP_SAML2::receiveAuthnRequest($idp);
        } catch (Exception $e) {
            if ($e->getMessage() === "Unable to find the current binding.") {
                throw new SimpleSAML_Error_Error('SSOPARAMS', $e, 400);
            } else {
                throw $e; // do not ignore other exceptions!
            }
        }
        assert('FALSE');
    }

    public function get_sls()
    {
        session_destroy();
    }

    public function action_login()
    {
        SimpleSAML_Configuration::setConfigDir(APPPATH.'config/simplesaml');
        // Retrieve the authentication state
        if (!array_key_exists('AuthState', $_REQUEST)) {
            throw new SimpleSAML_Error_BadRequest('Missing AuthState parameter.');
        }
        $authStateId = $_REQUEST['AuthState'];
        $state = SimpleSAML_Auth_State::loadState($authStateId, sspmod_core_Auth_UserPassBase::STAGEID);
        $source = SimpleSAML_Auth_Source::getById($state[sspmod_core_Auth_UserPassBase::AUTHID]);
        if ($source === NULL) {
            throw new Exception('Could not find authentication source with id ' . $state[sspmod_core_Auth_UserPassBase::AUTHID]);
        }

        if (array_key_exists('username', $_REQUEST)) {
            $username = $_REQUEST['username'];
        } elseif ($source->getRememberUsernameEnabled() && array_key_exists($source->getAuthId() . '-username', $_COOKIE)) {
            $username = $_COOKIE[$source->getAuthId() . '-username'];
        } elseif (isset($state['core:username'])) {
            $username = (string)$state['core:username'];
        } else {
            $username = '';
        }
        if (array_key_exists('password', $_REQUEST)) {
            $password = $_REQUEST['password'];
        } else {
            $password = '';
        }

        $errorCode = NULL;
        $errorParams = NULL;

        if (!empty($_REQUEST['username']) || !empty($password)) {
            // Either username or password set - attempt to log in
            if (array_key_exists('forcedUsername', $state)) {
                $username = $state['forcedUsername'];
            }
            if ($source->getRememberUsernameEnabled()) {
                $sessionHandler = SimpleSAML_SessionHandler::getSessionHandler();
                $params = $sessionHandler->getCookieParams();
                $params['expire'] = time();
                $params['expire'] += (isset($_REQUEST['remember_username']) && $_REQUEST['remember_username'] == 'Yes' ? 31536000 : -300);
                \SimpleSAML\Utils\HTTP::setCookie($source->getAuthId() . '-username', $username, $params, FALSE);
            }
            if ($source->isRememberMeEnabled()) {
                if (array_key_exists('remember_me', $_REQUEST) && $_REQUEST['remember_me'] === 'Yes') {
                    $state['RememberMe'] = TRUE;
                    $authStateId = SimpleSAML_Auth_State::saveState($state, sspmod_core_Auth_UserPassBase::STAGEID);
                }
            }
            try {
                sspmod_core_Auth_UserPassBase::handleLogin($authStateId, $username, $password);
            } catch (SimpleSAML_Error_Error $e) {
                /* Login failed. Extract error code and parameters, to display the error. */
                $errorCode = $e->getErrorCode();
                $errorParams = $e->getParameters();
            }
        }

        // $globalConfig = SimpleSAML_Configuration::getInstance();
        // $t = new SimpleSAML_XHTML_Template($globalConfig, 'core:loginuserpass.php');
        // $t->data['stateparams'] = array('AuthState' => $authStateId);
        // if (array_key_exists('forcedUsername', $state)) {
        //     $t->data['username'] = $state['forcedUsername'];
        //     $t->data['forceUsername'] = TRUE;
        //     $t->data['rememberUsernameEnabled'] = FALSE;
        //     $t->data['rememberUsernameChecked'] = FALSE;
        //     $t->data['rememberMeEnabled'] = $source->isRememberMeEnabled();
        //     $t->data['rememberMeChecked'] = $source->isRememberMeChecked();
        // } else {
        //     $t->data['username'] = $username;
        //     $t->data['forceUsername'] = FALSE;
        //     $t->data['rememberUsernameEnabled'] = $source->getRememberUsernameEnabled();
        //     $t->data['rememberUsernameChecked'] = $source->getRememberUsernameChecked();
        //     $t->data['rememberMeEnabled'] = $source->isRememberMeEnabled();
        //     $t->data['rememberMeChecked'] = $source->isRememberMeChecked();
        //     if (isset($_COOKIE[$source->getAuthId() . '-username'])) $t->data['rememberUsernameChecked'] = TRUE;
        // }
        // $t->data['links'] = $source->getLoginLinks();
        // $t->data['errorcode'] = $errorCode;
        // $t->data['errorparams'] = $errorParams;
        // if (isset($state['SPMetadata'])) {
        //     $t->data['SPMetadata'] = $state['SPMetadata'];
        // } else {
        //     $t->data['SPMetadata'] = NULL;
        // }
        // $t->show();
        // exit();

        return View::forge('saml/login', array('authstate' => $authStateId));
    }
}