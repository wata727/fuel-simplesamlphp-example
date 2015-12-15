<?php
$metadata['http://localhost:3000/saml/idp/metadata/'] = array(
    /*
     * The hostname for this IdP. This makes it possible to run multiple
     * IdPs from the same configuration. '__DEFAULT__' means that this one
     * should be used by default.
     */
    'host' => 'localhost',

    /*
     * The private key and certificate to use when signing responses.
     * These are stored in the cert-directory.
     */
    'privatekey' => 'saml.pem',
    'certificate' => 'saml.crt',

    /*
     * The authentication source which should be used to authenticate the
     * user. This must match one of the entries in config/authsources.php.
     */
    'auth' => 'example-sql',

    'SingleSignOnService' => 'http://localhost:3000/saml/idp/sso/',
    'SingleLogoutService' => 'http://localhost:3000/saml/idp/sls/',
);