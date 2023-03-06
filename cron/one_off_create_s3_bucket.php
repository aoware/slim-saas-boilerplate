<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../config.php');

$bucket_name = "aoware.avt.media";

$result = create_bucket($bucket_name);

print_r($result);

function create_bucket($bucket_name) {

    $client = new \Aws\S3\S3Client(
        [
            'region'  => CONF_aws_region,
            'version' => "latest",
            'credentials' => [
                'key'     => CONF_aws_key,
                'secret'  => CONF_aws_secret
            ]
        ]
    );

    $aws_account_id = CONF_aws_account_id;
    
    $bucket_policy = <<<JSON
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "Stmt1",
      "Action": "s3:*",
      "Effect": "Allow",
      "Resource": "arn:aws:s3:::$bucket_name",
      "Principal": {
        "AWS": [
          "arn:aws:iam::$aws_account_id:root"
        ]
      }
    }
  ]
}
JSON;

    echo $bucket_policy . "\r\n";
    
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

    // Adding the policy to ensure only the S3 user can access it

    try {
        $client->putBucketPolicy([
            'Bucket' => $bucket_name,
            'Policy' => $bucket_policy
        ]);

    } catch (\Aws\Exception\AwsException $e) {

        return [
            'success' => false,
            'message' => $e->getAwsErrorMessage() . " For bucket name `$bucket_name` when Adding policy to created Bucket"
        ];

    }

    // Making the bucket version enabled

    try {
        $client->putBucketVersioning([
            'Bucket' => $bucket_name,
            'VersioningConfiguration' => [
                'MFADelete' => 'Disabled',
                'Status' => 'Enabled'
            ],
        ]);

        return [
            'success'     => true,
            'bucket_name' => $bucket_name
        ];
    } catch (\Aws\Exception\AwsException $e) {

        return [
            'success' => false,
            'message' => $e->getAwsErrorMessage() . " For bucket name `$bucket_name` when setting versioning to created Bucket"
        ];

    }

}