<?php
return array(
    'router' => array(
        'routes' => array(
            'conference.rest.conference' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/conference[/:conference_id]',
                    'defaults' => array(
                        'controller' => 'Conference\\V1\\Rest\\Conference\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'conference.rest.conference',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
        ),
    ),
    'zf-rest' => array(
        'Conference\\V1\\Rest\\Conference\\Controller' => array(
            'listener' => 'Conference\\V1\\Rest\\Conference\\ConferenceResource',
            'route_name' => 'conference.rest.conference',
            'identifier_name' => 'conference_id',
            'collection_name' => 'conference',
            'resource_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => '25',
            'page_size_param' => '',
            'entity_class' => 'Conference\\V1\\Rest\\Conference\\ConferenceEntity',
            'collection_class' => 'Conference\\V1\\Rest\\Conference\\ConferenceCollection',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Conference\\V1\\Rest\\Conference\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Conference\\V1\\Rest\\Conference\\Controller' => array(
                0 => 'application/vnd.conference.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Conference\\V1\\Rest\\Conference\\Controller' => array(
                0 => 'application/vnd.conference.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Conference\\V1\\Rest\\Conference\\ConferenceEntity' => array(
                'identifier_name' => 'conference_id',
                'route_name' => 'conference.rest.conference',
                'hydrator' => 'ObjectProperty',
            ),
            'Conference\\V1\\Rest\\Conference\\ConferenceCollection' => array(
                'identifier_name' => 'conference_id',
                'route_name' => 'conference.rest.conference',
                'is_collection' => '1',
            ),
        ),
    ),
);
