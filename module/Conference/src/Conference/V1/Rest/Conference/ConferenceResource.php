<?php
namespace Conference\V1\Rest\Conference;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class ConferenceResource extends AbstractResourceListener
{
    protected $joindInService;

    public function __construct($joindInService)
    {
        $this->joindInService = $joindInService;
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $event = $this->joindInService->getEvent($id);
        return $event;

        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        $events = $this->joindInService->getHotEvents();
        return $events;
    }
}
