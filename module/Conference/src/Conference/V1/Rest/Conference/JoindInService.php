<?php
namespace Conference\V1\Rest\Conference;

use Zend\Paginator\Adapter\ArrayAdapter;
use Exception;

class JoindInService
{
    /**
     * Guzzle Client
     * @var \Guzzle\Http\Client
     */
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    protected function request($uri)
    {
        $request = $this->client->get(urldecode($uri));
        try {
            $response = $request->send();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 503);
        }

        return $response;
    }

    public function getHotEvents()
    {
        $uri = '/v2.1/events?filter=hot';
        $response = $this->request($uri);
        $events = $response->json();

        if (!isset($events['events'])) {
            return null;
        }

        $data = array();
        foreach ($events['events'] as $event) {

            $row = new ConferenceEntity();
            $row->conference_id = urlencode($event['uri']);
            $row->name = $event['name'];

            $data[] = $row;
        }


        $paginatorAdapter = new ArrayAdapter($data);
        $collection = new ConferenceCollection($paginatorAdapter);
        return $collection;
    }

    public function getEvent($uri)
    {
        $uri = urldecode($uri);
        $response = $this->request($uri);
        $events = $response->json();
        if (!isset($events['events'])) {
            return null;
        }
        $event = $events['events'][0];
        

        $entity = new ConferenceEntity;
        $entity->conference_id = urlencode($event['uri']);
        $entity->name = $event['name'];

        $this->getEventCommentData($entity, $event['comments_uri']);
        $this->getTalkData($entity, $event['talks_uri']);

        return $entity;
    }

    protected function getTalkData($entity, $talksUri)
    {
        $uri = urldecode($talksUri) . '?resultsperpage=1000';
        $response = $this->request($uri);
        $data = $response->json();
        if (!isset($data['talks'])) {
            return;
        }
        $talks = $data['talks'];

        $list = array();
        foreach ($talks as $talk) {
            $key = 'a' . $talk['average_rating'] . '.' . $talk['comment_count'];

            $talkData = array();
            $talkData['rating'] = $talk['average_rating'];
            $talkData['number_of_comments'] = $talk['comment_count'];
            $talkData['title'] = $talk['talk_title'];

            if (isset($talk['speakers'])) {
                $speakerList = array();
                foreach ($talk['speakers'] as $speaker) {
                    $speakerList[] = $speaker['speaker_name'];
                }
                $talkData['speaker'] = implode(", ", $speakerList);
            }
            $list[$key] = $talkData;
        }

        krsort($list);
        $topTalk = array_shift($list);
        $bottomTalk = array_pop($list);

        $entity->bestTalk = $topTalk;
        $entity->worstTalk = $bottomTalk;
    }

    protected function getEventCommentData($entity, $commentsUri)
    {
        $uri = urldecode($commentsUri);
        $response = $this->request($uri);
        $data = $response->json();
        if (!isset($data)) {
            return;
        }
        $comments = $data['comments'];
        $meta = $data['meta'];
        $count = isset($meta['count']) ? $meta['count'] : 0;

        $entity->numberOfEventComments = $count;
        if (!$count) {
            $entity->mostRecentComment = null;
        } else {
            $lastComment = array_pop($comments);
            $entity->mostRecentComment = '"' . $lastComment['comment'] . '" by ' . $lastComment['user_display_name'];
        }
    
    }
}
