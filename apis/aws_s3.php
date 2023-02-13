<?php

// List of methods for the S3 Client SDK object https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.S3Client.html

namespace apis;

class aws_s3 {

    private $client;

    public function __construct($region,$key,$secret) {

        $this->client = new \Aws\S3\S3Client(
            [
                'region'  => $region,
                'version' => "latest",
                'credentials' => [
                    'key'     => $key,
                    'secret'  => $secret
                ]
            ]
        );

    }

    public function add_file_to_bucket($agent_id,$storage_category,$file_name,$file_path,$acl) {

        $bucket_name = $this->get_bucket_name();

        $full_file_name = $agent_id . "/" . $storage_category . "/" . $file_name;

        try {
            $this->client->putObject(array(
                'Bucket'     => $bucket_name,
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
                'Bucket' => $bucket_name,
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

    public function get_file_public_url($agent_id,$storage_category,$file_name) {

        $bucket_name = $this->get_bucket_name();

        $full_file_name = $agent_id . "/" . $storage_category . "/" . $file_name;

        try {
            return $this->client->getObjectUrl($bucket_name,$full_file_name);

        } catch (\Aws\Exception\AwsException $e) {
            return false;
        }

    }

    /*
     * This return a Guzzle Stream object
     * https://docs.aws.amazon.com/aws-sdk-php/v2/guide/service-s3.html
     */

    public function get_file_from_bucket($agent_id,$storage_category,$file_name) {

        $bucket_name = $this->get_bucket_name();

        $full_file_name = $agent_id . "/" . $storage_category . "/" . $file_name;

        try {
            $result = $this->client->getObject(array(
                'Bucket'     => $bucket_name,
                'Key'        => $full_file_name
            ));
            return $result['Body'];
        } catch (\Aws\Exception\AwsException $e) {
            return $e->getAwsErrorMessage();
        }

    }

    public function get_bucket_name() {

        $server_name = strtolower(CONF_server_name);
        $server_name = str_replace("_","-",$server_name);

        return "bol." . $server_name . ".documents";
    }

}
