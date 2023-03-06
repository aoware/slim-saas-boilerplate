<?php

// https://developers.cloudflare.com/r2/examples/aws-sdk-php/

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../config.php');

$bucket_name           = "aoware.avt.media";
$cloudflare_account_id = CONF_cloudflare_account_id;
$access_key_id         = CONF_aws_key;
$access_key_secret     = CONF_aws_secret;

$credentials = new \Aws\Credentials\Credentials($access_key_id, $access_key_secret);

$options = [    
    'region' => 'auto',    
    'endpoint' => "https://$cloudflare_account_id.r2.cloudflarestorage.com",    
    'version' => 'latest',    
    'credentials' => $credentials
];

$s3_client = new \Aws\S3\S3Client($options);

create_bucket($bucket_name,$cloudflare_account_id);

function create_bucket($bucket_name,$cloudflare_account_id) {
    
    $client = new \Aws\S3\S3Client(
        [
            'region'  => 'auto',
            'endpoint' => "https://" . $cloudflare_account_id . ".r2.cloudflarestorage.com",
            'version' => "latest",
            'credentials' => [
                'key'     => CONF_aws_key,
                'secret'  => CONF_aws_secret
            ]
        ]
        );
        
    try {
        
        $client->createBucket([
            'Bucket' => $bucket_name,
            'ACL'    => 'private'
        ]);
        
    } catch (\Aws\Exception\AwsException $e) {
        
        return [
            'success' => false,
            'message' => $e->getAwsErrorMessage() . " For bucket name `$bucket_name` when creating Bucket"
        ];
        
    }
    
}