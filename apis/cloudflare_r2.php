<?php

// List of methods for the S3 Client SDK object https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.S3Client.html

namespace apis;

class cloudflare_r2 {

    private $client;
    private $bucket_name;

    public function __construct($cloudflare_account_id,$aws_key,$aws_secret,$bucket_name) {
        
        $this->client = new \Aws\S3\S3Client(
            [
                'region'  => 'auto',
                'endpoint' => "https://" . $cloudflare_account_id . ".r2.cloudflarestorage.com",
                'version' => "latest",
                'credentials' => [
                    'key'     => $aws_key,
                    'secret'  => $aws_secret
                ]
            ]
        );
        
        $this->bucket_name = $bucket_name;

    }

    public function add_file_to_bucket($account_id,$folder_id,$file_name,$file_path,$acl) {

        $full_file_name = $account_id . "/" . $folder_id . "/" . $file_name;

        try {
            $this->client->putObject(array(
                'Bucket'     => $this->bucket_name,
                'Key'        => $full_file_name,
                'SourceFile' => $file_path
            ));

            switch($acl) {
                case 'public' :
                    $s3_acl = 'public-read';
                    break;
                case 'private' :
                    $s3_acl = 'private';
                    break;
            }

            $this->client->putObjectAcl([
                'Bucket' => $this->bucket_name,
                'Key'    => $full_file_name,
                'ACL'    => $s3_acl
            ]);

            return [
                'success' => true,
                'key'     => $full_file_name
              ];

        } catch (\Aws\Exception\AwsException $e) {
            return [
                'success' => false,
                'error'   => $e->getAwsErrorMessage()
            ];
        }

    }

    public function get_file_public_url($account_id,$folder_id,$file_name) {

        $full_file_name = $account_id . "/" . $folder_id . "/" . $file_name;

        try {
            return $this->client->getObjectUrl($this->bucket_name,$full_file_name);

        } catch (\Aws\Exception\AwsException $e) {
            return false;
        }

    }

    /*
     * This return a Guzzle Stream object
     * https://docs.aws.amazon.com/aws-sdk-php/v2/guide/service-s3.html
     */

    public function get_file_from_bucket($account_id,$folder_id,$file_name) {

        $full_file_name = $account_id . "/" . $folder_id . "/" . $file_name;

        try {
            $result = $this->client->getObject(array(
                'Bucket'     => $this->bucket_name,
                'Key'        => $full_file_name
            ));
            return $result['Body'];
        } catch (\Aws\Exception\AwsException $e) {
            return $e->getAwsErrorMessage();
        }

    }

}
