<?php

return [
    'brokers' => env('KAFKA_BROKERS', 'localhost:9092'),

    'consumer_group' => env('KAFKA_CONSUMER_GROUP_ID', 'laravel-group'),

    'consumers' => [
        [
            'topic'   => env('KAFKA_CONSUMER_TOPIC', 'users.created'),
//            'handler' => \App\Kafka\Consumers\UserCreatedConsumer::class,
        ],
    ],

    'dlq_topic' => env('KAFKA_DQL_TOPIC', 'dlq.test'),

    // Optional advanced settings
    'consumer_timeout_ms' => env("KAFKA_CONSUMER_DEFAULT_TIMEOUT", 2000),
    'auto_commit' => env('KAFKA_AUTO_COMMIT', true),
    'offset_reset' => env('KAFKA_OFFSET_RESET', 'latest'),
];
