<?php
namespace Conference\V1\Rest\Conference;

use Zend\Paginator\Adapter\ArrayAdapter;

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

    public function getHotEvents()
    {
        $request = $this->client->get('/v2.1/events?filter=hot');
        $response = $request->send();
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
        $request = $this->client->get(urldecode($uri));
        $response = $request->send();
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
        
        $request = $this->client->get($uri);
        $response = $request->send();
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

        $entity->BestTalk = $topTalk;
        $entity->WorstTalk = $bottomTalk;
    }

    protected function getEventCommentData($entity, $commentsUri)
    {
        $request = $this->client->get(urldecode($commentsUri));
        $response = $request->send();
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
