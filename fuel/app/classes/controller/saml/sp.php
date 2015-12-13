<?php

class Controller_Saml_Sp extends Controller
{
    private static $settingInfo = array(
        'strict' => false,
        'debug' => true,
        'sp' => array(
            'entityId' => 'http://localhost:3000/saml/sp/metadata/',
            'assertionConsumerService' => array(
                'url' => 'http://localhost:3000/saml/sp/acs/',
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            ),
            'singleLogoutService' => array(
                'url' => 'http://localhost:3000/saml/sp/sls/',
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
            'x509cert' => 'MIIEjzCCA3egAwIBAgIJAJkos7sqcyuLMA0GCSqGSIb3DQEBBQUAMIGLMQswCQYDVQQGEwJKUDERMA8GA1UECBMIU2hpenVva2ExDTALBgNVBAcTBEZ1amkxITAfBgNVBAoTGEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDESMBAGA1UEAxMJbG9jYWxob3N0MSMwIQYJKoZIhvcNAQkBFhR3YXRhc3NiYXNzQGdtYWlsLmNvbTAeFw0xNTEyMTIxMjAxMDdaFw0yNTEyMTExMjAxMDdaMIGLMQswCQYDVQQGEwJKUDERMA8GA1UECBMIU2hpenVva2ExDTALBgNVBAcTBEZ1amkxITAfBgNVBAoTGEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDESMBAGA1UEAxMJbG9jYWxob3N0MSMwIQYJKoZIhvcNAQkBFhR3YXRhc3NiYXNzQGdtYWlsLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAManPR2lGLLLIu+qBke1rsQqxiDMPQ55yLC8zAPqtzNU4v20rB0votTF33d/d7wvksFsVvijNpQPL5vFZGwAqaod08vB9R/V533mDn9Cicui1wlTgBzMxQwNzdytoE2C9QiMEzrumAYdyX4BQ6Ui7xGAyWcc+D98hgD25k5O/XoHP7UZL9Ob7Ijya6LQmjoA38/5GLO6CnU7s7UL1wmYQfXhPV0Gwlg1+wiroLBnkNASJhIete4nQg6z8uE1C2+lbNNZswZ1rVLBdMNIDS4lOe9gA1Sb22gCilwsuz8Ut5JPbscxOQPJmX3zqfEYtxJWigUoWPdx+spMUPclF+MPcD8CAwEAAaOB8zCB8DAdBgNVHQ4EFgQUrCm0R24Z1A1vKMgBiLsV2m3qA2EwgcAGA1UdIwSBuDCBtYAUrCm0R24Z1A1vKMgBiLsV2m3qA2GhgZGkgY4wgYsxCzAJBgNVBAYTAkpQMREwDwYDVQQIEwhTaGl6dW9rYTENMAsGA1UEBxMERnVqaTEhMB8GA1UEChMYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMRIwEAYDVQQDEwlsb2NhbGhvc3QxIzAhBgkqhkiG9w0BCQEWFHdhdGFzc2Jhc3NAZ21haWwuY29tggkAmSizuypzK4swDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAcvU2H5J/X1puQbONAHae9BqMSIVJWHNP8Gflj2yNGxdfQ87UYlQ6ZgsBNGRoEA03h4NL7lVNO8hiFnFAIZtQKb6YA2RFWvc+Xh3cOiAirk2TGiH5PPWlOlAYB3Nm3WpAX/rOUnbhJIy4CnosqQ4VoumoVWgKMtZ+iENpI5qzfYR3z82JuTezDjrYbs1P93ZF3sFrgO4XgYeshdJTFZQNuEEFuAggt2A6e5VTbPUbYBHNnppEOpmy90OBXi/jjYqv06OtPhztbbXGV7Hu0qCuSuClHOfWg3N0qv38J0oWFu1CxsTI65jp1zhGSew/EbVgKIgnMJQmuTIr7+3GYidliQ==',
            'privateKey' => 'MIIEpAIBAAKCAQEAxqc9HaUYsssi76oGR7WuxCrGIMw9DnnIsLzMA+q3M1Ti/bSs
HS+i1MXfd393vC+SwWxW+KM2lA8vm8VkbACpqh3Ty8H1H9XnfeYOf0KJy6LXCVOA
HMzFDA3N3K2gTYL1CIwTOu6YBh3JfgFDpSLvEYDJZxz4P3yGAPbmTk79egc/tRkv
05vsiPJrotCaOgDfz/kYs7oKdTuztQvXCZhB9eE9XQbCWDX7CKugsGeQ0BImEh61
7idCDrPy4TULb6Vs01mzBnWtUsF0w0gNLiU572ADVJvbaAKKXCy7PxS3kk9uxzE5
A8mZffOp8Ri3ElaKBShY93H6ykxQ9yUX4w9wPwIDAQABAoIBAQCp679xLhkURoHZ
svuwNw4IqfXTdB/909oAo113NDwEYmXPUc2vIWIM0jy1wIiwpZjIbl1uTF2RA/Rb
L9sYLvDxnJXfwkZkNtnObQyVelrXnrCFP7Fj8RvTlbMzQijOZGgoKXkBfbj38l65
s2cjR+BqfljIJJWL6H3PMWIKNbLHvw1K6djtu8LJpCqIrMbP/IB6ozYcl+PIPf8s
1dF9GD8tQTJRJQkaKS8y+Bb55DwmLQ0Uz18zofjCbr0U+EjeMFmjhQyKTU/1ZJQ4
DE5/tcHM5LVoLsB3PVk9ET1IhWlxpxB4+wvdA17m/Iy27xSfFTol+O6kqMNfhRWg
O78vcOvRAoGBAO1ACCaftJkw2TZQm7mZcVVIbTfTWeGDLVUmfYcx7aMtWhV6nemn
xhJM15koCOrA5xgirMQBfKzQ5pPj12cxxoSBpHZMfc39shfLF8phGe2Lm2/pQo1G
QZ6DYIuJD96MTz8R78wcKphVHXPr2QgTT2GcjINwV8/jHPkKCMzECJplAoGBANZa
U/BVnKFYmLECySIS2ZxhTBGvrSTFXg+YR0esSw5no6Bl5ZuGyZxzbAJosIeGQMlD
Dy0ouzjkTBcMGdFUwzmWt34t6ISZDkKB1ebYiGRC7lUWxdpIq/6WMdbod/lxMB4r
9JUQZWaMOEJoYjNiUVxvpUjLB65zOaiyKpuudQPTAoGBAMqveocsx4hcYCVz5iZA
vb8DOjOIP3BWtgLZ+EHo2MEgPKa+82urpp75wPMabcvIc2V+MiTdtFqbuXE9JEgI
ETYg35UlMhEqnNyQ6ElAfXsgWvHX0MCK9gJq8K8ksEcLjaQvObMhmQ49IQVoxyiL
/rRAnxango2a1KS5+tyc2VC1AoGAHLXYjFZLNmkxenQPEJtJvmJh1/SZ0lUFNj2F
PppbK0DCb9d2G5DALE5hZykyi9R1lP+AZuIPwZ0CfAvI4Xri8zG2vhXUEP6XJF1T
kynpitRUK91y/rvBHcZsQoa8mxKICWKFzfl2O3gIiQyGKq++ig0CLG/VRY51aJ0E
i/mf/rUCgYBz6W49CvNBH+iPGa+bUwjRYoShzPF/iEs37VcYXCpPLPvz1smaAMij
H1Q2Gup35krBqCE9V84c6qcRU0o01mNaGj8Yh8ZPfgGoCGhXCUsPvp5CT0xqVQ5J
HDax+CDb3dLsPpZv9nAZVf7HAdoqCMy3LA1oyxIwWm67OERmCRiJgA==',
        ),
        // 'idp' => array(
        //     'entityId' => 'http://idp.platform.com:8080/openam/',
        //     'singleSignOnService' => array(
        //         'url' => 'http://idp.platform.com:8080/openam/SSORedirect/metaAlias/idp',
        //         'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        //     ),
        //     'singleLogoutService' => array(
        //         'url' => 'http://idp.platform.com:8080/openam/IDPSloRedirect/metaAlias/idp',
        //         'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        //     ),
        //     'x509cert' => 'MIICQDCCAakCBEeNB0swDQYJKoZIhvcNAQEEBQAwZzELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWExFDASBgNVBAcTC1NhbnRhIENsYXJhMQwwCgYDVQQKEwNTdW4xEDAOBgNVBAsTB09wZW5TU08xDTALBgNVBAMTBHRlc3QwHhcNMDgwMTE1MTkxOTM5WhcNMTgwMTEyMTkxOTM5WjBnMQswCQYDVQQGEwJVUzETMBEGA1UECBMKQ2FsaWZvcm5pYTEUMBIGA1UEBxMLU2FudGEgQ2xhcmExDDAKBgNVBAoTA1N1bjEQMA4GA1UECxMHT3BlblNTTzENMAsGA1UEAxMEdGVzdDCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEArSQc/U75GB2AtKhbGS5piiLkmJzqEsp64rDxbMJ+xDrye0EN/q1U5Of+RkDsaN/igkAvV1cuXEgTL6RlafFPcUX7QxDhZBhsYF9pbwtMzi4A4su9hnxIhURebGEmxKW9qJNYJs0Vo5+IgjxuEWnjnnVgHTs1+mq5QYTA7E6ZyL8CAwEAATANBgkqhkiG9w0BAQQFAAOBgQB3Pw/UQzPKTPTYi9upbFXlrAKMwtFf2OW4yvGWWvlcwcNSZJmTJ8ARvVYOMEVNbsT4OFcfu2/PeYoAdiDAcGy/F2Zuj8XJJpuQRSE6PtQqBuDEHjjmOQJ0rV/r8mO1ZCtHRhpZ5zYRjhRC9eCbjx9VrFax0JDC/FfwWigmrW0Y0Q==',
        // ),
        // 'security' => array(
        //     'authnRequestsSigned' => true,
        // ),
        'idp' => array(
            'entityId' => 'http://localhost:3000/saml/idp/metadata/',
            'singleSignOnService' => array(
                'url' => 'http://localhost:3000/saml/idp/sso/',
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            'singleLogoutService' => array(
                'url' => 'http://localhost:3000/saml/idp/sls/',
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            'x509cert' => 'MIIEjzCCA3egAwIBAgIJAJkos7sqcyuLMA0GCSqGSIb3DQEBBQUAMIGLMQswCQYDVQQGEwJKUDERMA8GA1UECBMIU2hpenVva2ExDTALBgNVBAcTBEZ1amkxITAfBgNVBAoTGEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDESMBAGA1UEAxMJbG9jYWxob3N0MSMwIQYJKoZIhvcNAQkBFhR3YXRhc3NiYXNzQGdtYWlsLmNvbTAeFw0xNTEyMTIxMjAxMDdaFw0yNTEyMTExMjAxMDdaMIGLMQswCQYDVQQGEwJKUDERMA8GA1UECBMIU2hpenVva2ExDTALBgNVBAcTBEZ1amkxITAfBgNVBAoTGEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDESMBAGA1UEAxMJbG9jYWxob3N0MSMwIQYJKoZIhvcNAQkBFhR3YXRhc3NiYXNzQGdtYWlsLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAManPR2lGLLLIu+qBke1rsQqxiDMPQ55yLC8zAPqtzNU4v20rB0votTF33d/d7wvksFsVvijNpQPL5vFZGwAqaod08vB9R/V533mDn9Cicui1wlTgBzMxQwNzdytoE2C9QiMEzrumAYdyX4BQ6Ui7xGAyWcc+D98hgD25k5O/XoHP7UZL9Ob7Ijya6LQmjoA38/5GLO6CnU7s7UL1wmYQfXhPV0Gwlg1+wiroLBnkNASJhIete4nQg6z8uE1C2+lbNNZswZ1rVLBdMNIDS4lOe9gA1Sb22gCilwsuz8Ut5JPbscxOQPJmX3zqfEYtxJWigUoWPdx+spMUPclF+MPcD8CAwEAAaOB8zCB8DAdBgNVHQ4EFgQUrCm0R24Z1A1vKMgBiLsV2m3qA2EwgcAGA1UdIwSBuDCBtYAUrCm0R24Z1A1vKMgBiLsV2m3qA2GhgZGkgY4wgYsxCzAJBgNVBAYTAkpQMREwDwYDVQQIEwhTaGl6dW9rYTENMAsGA1UEBxMERnVqaTEhMB8GA1UEChMYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMRIwEAYDVQQDEwlsb2NhbGhvc3QxIzAhBgkqhkiG9w0BCQEWFHdhdGFzc2Jhc3NAZ21haWwuY29tggkAmSizuypzK4swDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAcvU2H5J/X1puQbONAHae9BqMSIVJWHNP8Gflj2yNGxdfQ87UYlQ6ZgsBNGRoEA03h4NL7lVNO8hiFnFAIZtQKb6YA2RFWvc+Xh3cOiAirk2TGiH5PPWlOlAYB3Nm3WpAX/rOUnbhJIy4CnosqQ4VoumoVWgKMtZ+iENpI5qzfYR3z82JuTezDjrYbs1P93ZF3sFrgO4XgYeshdJTFZQNuEEFuAggt2A6e5VTbPUbYBHNnppEOpmy90OBXi/jjYqv06OtPhztbbXGV7Hu0qCuSuClHOfWg3N0qv38J0oWFu1CxsTI65jp1zhGSew/EbVgKIgnMJQmuTIr7+3GYidliQ==',
        ),
        'security' => array(
            'authnRequestsSigned' => true,
        ),
    );

    public function get_login()
    {
        $auth = new OneLogin_Saml2_Auth(self::$settingInfo);
        $returnTo = 'http://localhost:3000/';
        $auth->login($returnTo);
    }

    public function get_logout()
    {
        $auth = new OneLogin_Saml2_Auth(self::$settingInfo);
        $returnTo = 'http://localhost:3000/';
        $parameters = array();
        $nameId = null;
        $sessionIndex = Arr::get($_SESSION, 'sso.sessionIndex');
        $auth->logout($returnTo, $parameters, $nameId, $sessionIndex);
    }

    public function get_metadata()
    {
        try {
            $settings = new OneLogin_Saml2_Settings(self::$settingInfo, true);
            $metadata = $settings->getSPMetadata();
            $errors = $settings->validateMetadata($metadata);
            if (empty($errors)) {
                header('Content-Type: text/xml');
                echo $metadata;
            } else {
                throw new OneLogin_Saml2_Error(
                    'Invalid SP metadata: '.implode(', ', $errors),
                    OneLogin_Saml2_Error::METADATA_SP_INVALID
                );
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function post_acs()
    {
        $auth = new OneLogin_Saml2_Auth(self::$settingInfo);
        $auth->processResponse();
        $errors = $auth->getErrors();

        if (empty($errors)) {
            if ($auth->isAuthenticated()) {
                echo "SSO Success!";
                Debug::dump($auth->getAttributes());
                Debug::dump($auth->getSessionIndex());
            } else {
                echo "SSO failed";
            }
        } else {
            echo "Error ocured";
        }
    }

    public function get_sls()
    {
        $auth = new OneLogin_Saml2_Auth(self::$settingInfo);
        $auth->processSLO();
        $errors = $auth->getErrors();

        if (empty($errors)) {
            echo "SLO Success!";
        } else {
            echo "Error ocured";
        }
    }
}