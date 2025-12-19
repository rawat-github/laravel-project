<?php
namespace App\Services;
use ValueResearch\Scaffold\Loggers\Log;

class KafkaTopicDataService
{

    public function handleTopicDataManual($messages, $action, $isUpdate = false, $meta = []): void
    {
        /*
         * Sample Incoming array
         * {"topic_name":[{data1...}, {data2...}]}
         *
         * */
        Log::info("Batch Manual processing started for topic: " . json_encode($messages));
    }

}